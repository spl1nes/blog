# Php Workflow

In this article I will illustrate a development workflow I'm using for one of my projects. Together we will go through various topics which hopefully will help you to implement your own workflow or potentially extend your current workflow. This article is mostly directed at people who either have not yet figured out how to implement their own workflow or are open minded for some other ideas. In this article you **won't** find a in detail explenation of all the features of the tools and services that are used nor will I be able to provide all the alternatives as this would certainly go beyond the scope of this article.

The following workflow is by no means a business standard and you'll most likely find 9 different workflows in 10 different projects/companies. That beeing said you will always find the reasoning for decisions I made and with these reasons in mind you should be able to check whether these steps make sense for you as well or if you should approach certain steps in a different way. While this article focuses on PHP I will make some references to JavaScript as most of the time JavaScript also plays a very substential role in many projects.

The following topics will be discussed:

1. Development Environment
3. Version Control
2. Code Quality
4. Build Process

## Development Environment

The development environment is probably one of the hot topics especially for developer who are a little bit less experienced. I personally consider this on of the least important parts as there are so many possibilities and none of them are a bad decision. In general the following mostly depends on personal preferences or on the committed infrastrucutre of a company/project.

### OS

It Doesn't matter. You can work on Windows, Linux, macOS whatever fits your personal experience and preference. That beeing said I recommend anyone to try different operating systems and distributions no matter what. 

Personally I do most of my development work on Windows but I certainly see the advantages Linux and macOS provide. The biggest advantage of Linux and macOS is the command line which allows developers to integrate and access some very useful command line commands and tools into the development process without much effort. Writing custom command line scripts for Linux and macOS is from my personal experience much easier than it is on windows. This is however no show stopper on Windows and could be considered a nice to have. All significant tools which are developed to support the development process are also available on Windows. 

Very often the server that is used for the php project uses Linux anyways which allows developers to do some handy stuff on the server instead of the local development machine. If you don't have a server I recommend to purchase a Raspberry Pi which can be used to setup a small local server and helps to simulate the build and release process you will find further down the article.

#### Vagrant & Docker

Vagrant & Docker are the two big (indipendent) topics when talking about development environment. In fact when developers speak about development environment they usually mean on of the two. I put them as part of the OS as they basicallay negate some of the already very few shortfalls of locking onselfe in a OS. Also Vagrant & Docker are very different the basic effect both provide to the developer is very similar. 

Both Vagrant & Docker allow you to create a "*simulated*" environment on your local OS where you can run your php application. The reason why many developers and companies are using one of the two is that they allow to recreate the exact same environment with minimal effort. All someone has to do is share the configuration file and another developer can have the same setup without manually installing all the dependencies and doing the configuration manually. 

The biggest strength they provides is when you are working in teams or if the project is a open source project because it allows other developers to get started very quickly without much effort of re-configuring their current development environment.

For Vagrant I recommend you to have a look at https://puphpet.com. This page helps you with creating your development environment with vagrant. If you would like to use Docker you can find some configuration files at https://docs.docker.com/samples/library/php. In both cases it is highly recommended to read more about either of them as their implementation will not be part of this article. 

Wether you should use Vagrant or Docker instead of installing webserver, database and tools directly on your local OS depends on your team size. For less experienced developers I recommend to not bother with them at the beginning and integrate them later on as they can be added at a later stage without much effort.

### IDE/Editor

The IDE and editor discussion is like discussing about religon or politics. Every developer has to try them by themselves as this is again a matter of preferences. Whenever you test a new editor I highly recommend to test it at least for 1 month where you actively develop with this editor, configer the editor and install tools for this editor. In the following you will find a list of key editors/IDEs and a very short **personal opinnion**.

* PHPStorm: The defacto most professional PHP IDE with lots of integrated tools and extensions. I would rank it number 1 or 2.
* Visual Studio Code: All around editor with a huge amount of extensions and decent performance. I would rank it number 1 or 2.
* Sublime: All around editor with very good performance but less extensions. I would rank it number 3.
* Atom: All around editor with sometimes questional performance but large amount of extensions. I would rank it number 4.
* emacs/vim: Both command line editors, I mention them since I don't want to get attacked in a dark side road.

## Version Control

Version control allows developers to save different versions of a project without copying the source code folder into different places. Not only is version control used to save different versions of a project but also to work in parallel on different feautres and bugs without compromising the current version. Additionally version control is extremely powerfull for collaboration with multiple developers who can work on the same project and even same files. Version control allows you to combine (merge) contributions by other developers into the source code automatically. Version control allows you to easily track back which developer implemented which part of the code and how the code got changed over time. Think of it as a replay functionality where developers can make annotations to explain what and why they changed or implemented certain aspects.

While explaining how to use version control is a much too big topic to discuss in this article I recommend to especially check out the version control system **Git** as this is what established itself as the leading version control system for open source and public facing projects.

If you also decided to use **Git** you will most likely come accross multiple Git UI tools which can be used to create this code history of your project with annotations. Git has a very powerfull but sometimes confusing command line implementation which is why I don't recommend to start with the command line tool in the beginning. Recommended Git UI applications are:

* Sourcetree: Powerfull GUI that has integrated a lot of features but requires some time for beginners to get used to
* GitKraken: Decent GUI with a good amount of integrated Git features
* GitHub Desktop: Very simple user interface recommended for complete beginners
* None: The command line is obviously the most powerfull and you should learn it however probably not in the beginning when everythign is new to you.

### Remote Version Control

While version control is already a powerfull tool if you only use it on your local development machine it becomes even more powerfull if you additionally use a remote version control. This means you as a developer can have the history of your project on your local machine and the same history is also available on a remote server. Whenever you create a new local history you can share this information with the remote version control system and other developers can download these changes or integrate their changes into the source code.

The 3 biggest remote version control providers for Git are Github, Bitbucket and Gitlab. Have a look at what their differences are and fit your requirements the best. I used all 3 of them but decided to stay with Github because of its clean website layout. 

## Code Quality

Code quality is a very important factor to ensure the long livety of a code base and cosists of coding style, tests, updates, security, compatibility, code metrics, documentation etc. Many teams consider different points as critical parts of code quality but with the following you'll get a decent overview of the most common aspects.

### Linting

While linting can mean detailed code analysis in most cases it referes to simple compilation checks. Linters either compile code in order to see if the code is valid or statically analyse the code in terms of validity. 

Simple linting tests can be done via:

```bash
php -l {php_file_name}
```

The output will show the errors if any exists. For css and js linting you can use `PHPCS` further below.

### PhpUnit (Unit & Integration Tests) [important]

Tests are very important to ensure that the written code behaves in the expected manner. With `PhpUnit` it's possible to run unit tests but also perform integration tests. Unit tests are basically tests of very small code sections (e.g. methods/function) where the actual result is tested against the expected result. Integration tests focus on bigger code groups such as the interaction of different components, modules and even test the end result of a request. Automated tests help with generating reproducable tests that can be used to test many aspects which may not be possible with manual tests. `PhpUnit` allows to generate reports which also show how much of the code is actually covered by tests. This helps with identifying uncovered code sections and with keeping track of test coverage. Other testing frameworks are `Behat` and `Codeception` which I'm not mentioning here as I never really used them to a degree I can make usefull comments. However where these two shine the most compared to `PhpUnit` is that it is much easier to test whole concepts or application behavior (e.g. test API requests)

In my projects I use `PhpUnit` for unit tests, integration tests and try to achieve 90% coverage. I also use it in order to tear down an old testing application, set up a new testing application and generate a large amount of random testing data which can be used afterwards for live and ui tests in the web application or as demo data for other people/developers. This allows to set up a new demo application in a matter of seconds and test changes in the code really quick without the need of performing manual tests. Unit tests could be used as `pre-commit` hook but depending on the project size these tests may take a long time which would discourage contributors from committing code changes. As a result unit tests should only be run before merging code. 

### PhpStan & Phan (Static Code Analysis) [important]

A different form of tests can be provided by static code analysis tools such as `PhpStan` and `Phan`. Static code analysis tools analyse the code without running it. These static analysis tools help to find code sections that could potentially cause bugs or errors but are not always found during unit or integration tests (even if 100% code coverage is achieved). 

I use `PhpStan` and `Phan` as `pre-commit` hook. This means that changes will only be accepted if `PhpStan` and `Phan` don't find any potential bugs. A sample configuration can be found at https://github.com/Orange-Management/Build/blob/develop/Config/phpstan.neon and https://github.com/Orange-Management/Build/blob/develop/Config/phan.php

### PHPMD [situational]

With `PHPMD` you can analyse code for subjectively bad code patterns such as too long/short function names, too complexe classes/methods, unused or badly named variables etc. It's possible to define your own limits for these inspections and the error level.

In my projects `PHPMD` is configured very vorgifing as I find many of the rules very situational and don't wont most of these rules to prevent a commit as I also use this inspection as a `pre-commit` hook. Additinally to my pre-commit hook I run it with much lower error level filtering for manual code inspection sessions. A sample configuration can be found at https://github.com/Orange-Management/Build/blob/develop/Config/phpmd.xml

### PHPCS [important]

`PHPCS` inspects the code style and enables you to define a fixed code style which can be shared with a team. This allows a whole team to write code in the same code style and makes collaborated code look the same even if it is written by different people. A uniform code style also helps with the readability for other developers and makes the code look cleaner. While `PHPCS` provides some of the same rules as `PHPMD` it focuses much more on code style and allows to define these rules much more granular. 

`PHPCS` is used as a `pre-commit` hook in order to prevent any commits which don't follow the coding style. A sample configuration can be found at https://github.com/Orange-Management/Build/blob/develop/Config/phpcs.xml

### Custom Scripts

While you will not hear about custom inspection scripts to ensure code quality as often as the above mentioned tools I really came to like my own inspection scripts. These inspection scripts are linux only but since some of the tools above allow you to create your own inspection rule you could potentially re-implement them for `PHPCS` etc. as well. 

In the beginning a delegator or routing script is required since you can only have one `pre-commit` script. In my cases I'm also calling some of the above mentioned tools in this `pre-commit` hook script (not shown below).

```bash
#!/bin/bash

. logging.sh
. syntax.sh
. filename.sh
```

The following scripts are all used as `pre-commit` hooks in my projects:

#### Logging

Inform about logging code in the committed code but don't abort the commit if it is found.

```bash
#!/bin/bash

git diff --cached --name-only | while read FILE; do
if [[ "$FILE" =~ ^.+(php)$ ]]; then
    RESULT=$(grep "var_dump(" "$FILE")
    if [ ! -z $RESULT ]; then
      echo -e "\e[1;33m\tWarning, the commit contains a call to var_dump() in '$FILE'. Commit was not aborted, however.\e[0m" >&2
    fi
fi
done

git diff --cached --name-only | while read FILE; do
if [[ "$FILE" =~ ^.+(js)$ ]]; then
    RESULT=$(grep "console.log(" "$FILE")
    if [ ! -z $RESULT ]; then
      echo -e "\e[1;33m\tWarning, the commit contains a call to console.log() in '$FILE'. Commit was not aborted, however.\e[0m" >&2
    fi
fi

done || exit $?
```

##### Html syntax

In my projects template files have the ending `.tpl.php` and I'm checking some html tags for invalid attribues 

```bash
#!/bin/bash

git diff --cached --name-only | while read FILE; do

# Html/template checks
if [[ "$FILE" =~ ^.+(tpl\.php|html)$ ]]; then
    # Invalid and empty attributes
    if [[ -n $(grep -E '=\"[\#\$\%\^\&\*\(\)\\/\ ]*\"' $FILE) ]]; then
        echo -e "\e[1;31m\tFound invalid attribute.\e[0m" >&2
        grep -E '=\"[\#\$\%\^\&\*\(\)\\/\ ]*\"' $FILE >&2
        exit 1
    fi

    # Invalid class/id names
    if [[ -n $(grep -E '(id|class)=\"[a-zA-Z]*[\#\$\%\^\&\*\(\)\\/\ ]+[a-zA-Z]*\"' $FILE) ]]; then
        echo -e "\e[1;31m\tFound invalid class/id.\e[0m" >&2
        grep -E '(id|class)=\"[a-zA-Z]*[\#\$\%\^\&\*\(\)\\/\ ]+[a-zA-Z]*\"' $FILE >&2
        exit 1
    fi

    # Images must have a alt= attribute *error*
    if [[ -n $(grep -P '(\<img)((?!.*?alt=).)*(>)' $FILE) ]]; then
        echo -e "\e[1;31m\tFound missing image alt attribute.\e[0m" >&2
        grep -P '(\<img)((?!.*?alt=).)*(>)' $FILE >&2
        exit 1
    fi

    # Input elements must have a type= attribute *error*
    if [[ -n $(grep -P '(\<input)((?!.*?type=).)*(>)' $FILE) ]]; then
        echo -e "\e[1;31m\tFound missing input type attribute.\e[0m" >&2
        grep -P '(\<input)((?!.*?type=).)*(>)' $FILE >&2
        exit 1
    fi

    # Form fields must have a name *error*
    if [[ -n $(grep -P '(\<input|select|textarea)((?!.*?name=).)*(>)' $FILE) ]]; then
        echo -e "\e[1;31m\tFound missing form element name.\e[0m" >&2
        grep -P '(\<input|select|textarea)((?!.*?name=).)*(>)' $FILE >&2
        exit 1
    fi

    # Form must have a id, action and method *error*
    if [[ -n $(grep -P '(\<form)((?!.*?(action|method|id)=).)*(>)' $FILE) ]]; then
        echo -e "\e[1;31m\tFound missing form element action, method or id.\e[0m" >&2
        grep -P '(\<form)((?!.*?(action|method|id)=).)*(>)' $FILE >&2
        exit 1
    fi

    # Inline css is invalid *warning*
    if [[ -n $(grep -P '(style=)' $FILE) ]]; then
        echo -e "\e[1;31m\tFound missing form element action, method or id.\e[0m" >&2
        grep -P '(style=)' $FILE >&2
    fi
fi

done || exit $?
```

##### General file syntax checks

Any files that are code shouldn't have whitespaces at the end of a line and tabs are not allowed


```bash
#!/bin/bash

git diff --cached --name-only | while read FILE; do

# text files in general
if [[ "$FILE" =~ ^.+(sh|js|php|json|css)$ ]]; then
    # Check whitespace end of line in code
    if [[ -n $(grep -P ' $' $FILE) ]]; then
        echo -e "\e[1;31m\tFound whitespace at end of line in $FILE.\e[0m" >&2
        grep -P ' $' $FILE >&2
        exit 1
    fi

    # Check for tabs
    if [[ -n $(grep -P '\t' $FILE) ]]; then
        echo -e "\e[1;31m\tFound tab instead of whitespace $FILE.\e[0m" >&2
        grep -P '\t' $FILE >&2
        exit 1
    fi
fi

done || exit $?
```

##### Bash syntax checks / linting

Most linting is done by the tools mentioned above. For bash scripts however I don't have a specific linter installed and I use bash itself for linting itself.

```bash
#!/bin/bash

git diff --cached --name-only | while read FILE; do

if [[ "$FILE" =~ ^.+(sh)$ ]]; then
    if [[ -f $FILE ]]; then
        # sh lint
        bash -n "$FILE" 1> /dev/null
        if [ $? -ne 0 ]; then
            echo -e "\e[1;31m\tBash linting error.\e[0m" >&2
            exit 1
        fi
    fi
fi

done || exit $?
```

##### Filename

File names in my projects should only contain ASCII characters:

```bash
#!/bin/bash

allownonascii="false"

git diff --cached --name-only | while read FILE; do

if [ "$allownonascii" != "true" ] &&
	test $(git diff --cached --name-only --diff-filter=A -z $FILE |
	    LC_ALL=C tr -d '[ -~]\0' | wc -c) != 0
then
    echo -e "\e[1;31m\tInvalid file name.\e[0m" >&2
    exit 1
fi

done || exit $?
```

## Build, Integration, Deployment Process

Very often people use either public services such as Travis, Codeclimate, Scrutinizer etc. to setup a build process which involves compiling, inspecting, deployment... or a automation server such as `Jenkins` which is managed on a server or local machine. 

Online services are great since they save a lot of time in terms of setup and maintenance but in my cases I prefer to have full control about my process which is why I created my own process on my server. This however is only recommended if you are prepared and interested in DevOps work. An example can be found at https://github.com/Orange-Management/Build (I recommend to start with the `buildProject.sh` file).

### Documentation

### PhpMetrics

### Pdepend

### Phploc

### Documentor

### 
