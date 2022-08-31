# Bug Hunt - Un-Initialized Global Values

Bugs can drive you crazy. More than just once I've hunted down a bug that seemed to be extremely difficult but was actually simple to solve once found. Very often it's more a matter of identifying the part of the code that is causing the bug instead of finding a solution for a problem that causes problems. But what do you do if the mere observation of a bug changes the effect or simply solves the bug. Follow me on one of my more interesting bug hunts with PHP and Apache2.

## The Bug

It was September 2015 when I was working on my `Request` implementation for http requests in my framework `phpOMS` when suddenly seemingly out of nowhere one of my controllers had problems with creating a view (on some page refreshes without changes to the URL). As it turned out some of the server variables which where required where not set properly or to be more precise they where empty. 

PHP bug report https://bugs.php.net/bug.php?id=69081

## The Hunt Begins

Immediately I thought that I must have messed up something in my controller recently (which I was actually also changing on that day and the previous days) as the code which I changed recently in the `Request` model didn't have anything to do with server values. While inspecting the controller to my surprise I realized that the server values were properly set. Mhh... Maybe there is a problem with the template file during the rendering process. So I continued the debugging only to find out that there was also no problem. After jumping through the relevant lines the page rendered correctly without any problems. Sweet, I thought, must have been server side caching problem.

Fast forward some page refreshes while continuing with some development I got the same problem again. Getting a little bit annoyed at this and still thinking it must be some caching problem (either client side or server side) I disabled all caching in the browser, disabled opcache and all http caches. Next page refresh, again no problems. Problem solved right? No suddenly I got a similar problem on a different page which also used server variables. 

Seems like it wasn't a caching problem. All right time to hit ALT+F5 like a mad man and let's see what happens. First view page refreshes were fine but suddenly again an error while creating a particular view which used server variables. OK we are on to something at least. Approximately 100 further page refreshes confirmed my suspicion. I have a bug which only sometimes happens without any noticeable changes from the client side. But HOW? Even though I already disabled all caching I still couldn't think about anything else which could cause such a weird behavior. 

Oh well, now let's have a look at my `Request` model. In this model I load all the get, post and server data into member variables and unset the super globals so no one can use them and is forced to use the `Request` model. Fine Let's not unset the super globals and use the `$_SERVER` variable directly. Success? No, still the same problem. Even if this would have worked I didn't want to use the super globals directly as any part of the application could potentially modify or unset them.

The next thought I had was:

> All right maybe somewhere before I get into my `Request` model some part of the application is already overwriting the `$_SERVER` variable.

So I decided to do a `var_dump($_SERVER)` right at the beginning of the application. And to my surprise every refresh dumped the correct contents and even more surprising the `Request` model also always had the correct values. If I removed the `var_dump()` I was back at square one.  

Seriously confused why the super globals have almost always the correct values at the very beginning of the application but in some cases not at a later stages I decided to time the application entry until first access to the super globals in the `Request` model. The timing was below 30ms. While this shouldn't be a problem it must have been somehow related to the delayed access since the access at the very beginning of the application had a 98% success rate (compared to like 70% success rate at a later stage).

Some online searches later I learned that super globals in PHP are only propagated once they become visible to the compiler (http://php.net/manual/en/ini.core.php#ini.auto-globals-jit). So I thought maybe for some reason the compiler doesn't propagate the super globals correctly in some cases (for whatever reason).

## The Solution

As a solution I set `auto_globals_jit` to `0` in the `php.ini` which always propagated the super globals, even if they are never accessed and much to my pleasing this solved my problem. To this day I don't know why that was happening since:

* I was not using variable variables
* The complexity of the application at least from my perspective was low and shouldn't have caused hiccups for the compiler
* The execution time was fast
* This bug only sometimes occurred (even if nothing was changed request wise)

Fast forward some weeks/months I re-installed Apache2 and PHP and didn't have this problem any longer.