# Employee Ranking Based on Intercompany Relations

You may have heard about the **PageRank** algorithm which made Google so successful in it's early days. This algorithm ranks websites based on their relation to other websites. This algorithm could be used to apply the same logic on employees and their relationships to other employees in order to determin the importance of employees.

## The PageRank Algorithm

The PageRank algorithm checks which website links to which other website and inferes a score based on the amount and quality of relations. A link from a high ranking website is more valuable than a link from a low ranking website. Since the rank of a page changes over time (based on the incoming links) the algorithm needs to "re-calculate" the ranks many times to come to a conclusion. 

The algorithm itself shall not be a topic of this post and can be checked out on https://en.wikipedia.org/wiki/PageRank.

## Possible Conclusions and Analysis

While this is most certainly a controversial topic it is definately an interesting though experiment. You could:

* Learn a lot about who the key people in the company are
* Find critical positions which need a backup
* Possible communication issues (which people/departments have a low rank and are possibly not involved enough)
* Which are the social groups in a company (this could be paired with a cluster analysis). Changes to single positions/people may effect the whole group/cluster (e.g. employee leaves the company, becomes sick, becomes demotivated etc.)

## Databasis 

The most simple databasis which most companies have are emails. Let's say we could use all the email history within the company to track who is communicating with whom and how often. In terms of the PageRank algorithm the people would be the "websites" and the emails sent to other employees are the "links" between websites.

## Implementation

```py
class PageRank:
    def __init__(self, relations, isUnique, damping = 0.85):
        self.pageRanks = {}
        self.relations = {}
        self.outgoing = {}
        self.damping = damping

        for key in relations:
            self.outgoing[key] = len(relations[key])

            if key not in self.relations:
                self.relations[key] = []

            for linkTo in relations[key]:
                if linkTo not in self.relations:
                    self.relations[linkTo] = []

                if linkTo not in self.outgoing:
                    self.outgoing[linkTo] = 0

                if isUnique or key not in self.relations[linkTo]:
                    self.relations[linkTo].append(key)

    def calculateRanks(self, iterations = 20, startRank = None):
        if startRank is not None:
            self.pageRanks = startRank
        else:
            for key in self.relations:
                self.pageRanks[key] = 0.0

        i = 0
        while i < iterations:
            for key in self.relations:
                PR = 0.0

                for linkFrom in self.relations[key]:
                    PR += self.pageRanks[linkFrom] / self.outgoing[linkFrom]

                self.pageRanks[key] = 1 - self.damping + self.damping * PR


            i += 1

        return self.pageRanks
```

