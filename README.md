Kata Authentication with Oauth
==============================

#### As a user, I need to be able to authenticate myself through Github.

```
[x] Use Github to authenticate the user (documentation: https://developer.github.com/v3/oauth/)
[x] I need to see the user currently authenticated.
```

### Steps :


0/ Create a github Oauth application (https://github.com/settings/applications/new)

![1](https://cloud.githubusercontent.com/assets/667519/4909786/300b1246-6477-11e4-93c9-a3239b775ad0.png)
![github_oauth_application](https://cloud.githubusercontent.com/assets/667519/4909451/6edcc9e6-6473-11e4-9ea2-fe971299a2d5.png)

----------------------

1/ Get the Guzzle / Buzz Bundle of your choice (I chose the famous ``"misd/guzzle-bundle": "1.1.4"`` allowing me to
create services as clients for the usefull endpoint ).

----------------------

2/ Launch the ``php app/console generate:bundle`` to generate a new bundle. I chose ``PoleDev/AppBundle``, but you can choose anything
you want.

--------------

3/ Create the User class (make sure you implement the ``UserInterface``).

--------------

4/ Create your first action having with the following route ``@Route("/", name="index")``.
![0](https://cloud.githubusercontent.com/assets/667519/4909782/26eaa33e-6477-11e4-91b9-a9720c48c219.png)
--------------

5/ Add the annotation ``@Template`` to point to the right template.

--------------

6/ Create the template to have a link for the user to click on to begin the Oauth : ``<a href="{{ path('github') }}">Connect with Github</a>``.

--------------

7/ Create the two parameters ``client_id`` and ``client_secret`` in the file ``app/config/parameters.yml``.

--------------

8/ Create the action having the route name ``github``. In this action, redirect the user to the link ``https://github.com/login/oauth/authorize?client_id=XXXXX``.
Use the parameter in the container.

--------------

9/ Update the file security.yml to indicate your custom provider ``oauth.provider.github`` and your authenticator ``oauth.authenticator.github``.

--------------

10/ Create the Authenticator class (``GithubAuthenticator``) in the folder ``src/PoleDev/AppBundle/Security``.

--------------

11/ Implement the ``SimplePreAuthenticatorInterface`` with all the method needed.

--------------

12/ In the method ``createToken()`` get the access token from Github.

--------------

13/ Make it a service.

--------------

14/ Create the Provider class (``GithubProvider``) in the ``src/PoleDev/AppBundle/Security``.

--------------

15/ Implement the ``UserProviderInterface`` with all the method needed.

--------------

16/ Create the action redirecting the user from the url ``/admin`` to ``/admin/github``.

--------------

17/ Create the action with the route ``/admin/github``.

--------------

18/ Create the template accordingly to display the fullname of the user authenticated.
You should get that:
![4](https://cloud.githubusercontent.com/assets/667519/4909798/46802606-6477-11e4-9f9b-aa8e10e06f99.png)

--------------

19/ Click on the link "Connect with Github", you should be redirected to the following page (Github):
![2](https://cloud.githubusercontent.com/assets/667519/4909791/377fee52-6477-11e4-871a-acc951584198.png)

--------------

NB: If you don't put any scope, here is the list of information you get from Github:
(you can get it by doing a ``var_dump()`` in the ``GithubAuthenticator::createToken method``)

![3](https://cloud.githubusercontent.com/assets/667519/4909796/3df87cf4-6477-11e4-874e-0fb7694ae828.png)


Et voilà !


Some documentation:
- http://symfony.com/doc/current/book/security.html
- http://symfony.com/doc/current/cookbook/security/api_key_authentication.html
- http://symfony.com/doc/current/components/security/authentication.html
- http://symfony.com/doc/current/components/security/firewall.html
