# Github Issue Tags

Issue tags on github are a great way to structure issues not only for yourself but also in teams. While the specific issue tags may depend on your project, language and team, this short article will give an example how one potential solution could look like. This can be helpful for public and private repositories. However, for open source repositories which would like to attract contributes this may have the biggest benefit.

## Tag Structuring

In order to structure issue tags alphabetically it is helpful to prefix different tag categories. Another feature which is very helpful is the customizable coloring of the tags.

One category should have a distinguishable color from the other. This allows users to easily browse issues for a specific issue category. At the same time this is also helpful in order to make sure that every issue has at least one tag of one category. Please note that very often the different categories could be extended with further levels which can be helpful but might be too much depending on your project size.

For organizations I would recommend to keep the structures per repository as similar to each other as possible. This uniform logic and visual appearance makes it easier to work with. The assignment of the levels to issues can be a challenge in itself and should probably be performed by a project manager who knows the code base decently and has a broad understanding of different requirements & implications.

### Experience

Issues may require different experience levels. Providing an experience category will allow people to find issues for their skill level more easily. This may be especially helpful for open source projects in order to attract new contributors who may want to look for beginner issues.

* exp_beginner
* exp_medium
* exp_expert

### Severity (Priority)

The severity should indicate how critical an issue is which will allow to find and fix critical issues fast.

* sev_low
* sev_medium
* sev_high

To be precise these look more like priorities than severities but for small to medium projects it might make sense to combine the severity and priority. If you would like to be more precise you should use two different structures for severity and priority which may or may not be helpful for your repository.

Priority:

* prio_low
* prio_medium
* prio_high

Severity:

* sev_low
* sev_medium
* sev_major
* sev_critical

Alternatively, you may want to have a look at ISO risk management and how the severity is categorized there.

### Time

Independent of the severity or priority an issue may take a long or short amount of time to fix. Sometimes it is helpful to be able to look for short issues if someone only has a limited amount of time (or in case of open source doesn't want to commit for a long time to a certain issue).

* time_short
* time_medium
* time_long

### Environment

The environment is probably the most project/repository dependent category. I use this category to define where these issues are effective. You may not even need this category or have completely different levels/elements in it.

* env_development (in the dev environment)
* env_docs (in the docs or related to docs)
* env_release (in the live/release environment)
* env_tests (in the tests or related to tests)

### Status

The status is one of the most common issue tags you will find (also on github). In my case I omit the open and closed levels since they can be seen in the issue and don't need additional tags (open = issue open, closed = issue closed).

* stat_backlog (in backlog or someone is already working on it (see assigned developer))
* stat_duplicate (probably closed in this case)
* stat_in progress (you may want to omit this since it can be seen by the assigned developer)
* stat_invalid (probably closed in this case)
* stat_wontfix (probably closed in this case)

### Type

The type of the issue describes its nature and is fairly self-explanatory.

* type_bug
* type_security
* type_enhancement
* type_feature
* type_optimization
* type_question
* type_todo

![Github issue tags](/content/blog/dev/img/github_issue_tags.png "Github issue tags")
