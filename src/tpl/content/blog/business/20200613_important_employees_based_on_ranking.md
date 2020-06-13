# Important Employees Based on Ranking

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

```php
<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Marketing
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

/**
 * PageRank algorithm
 *
 * @package phpOMS\Business\Marketing
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class PageRank
{
    /**
     * Damping value
     *
     * @var float
     * @since 1.0.0
     */
    private float $damping = 0.85;

    /**
     * Page rank
     *
     * @var array<mixed, float>
     * @since 1.0.0
     */
    private array $pageRanks = [];

    /**
     * Relation array
     *
     * Array of elements where every element has an array of incoming links/relations
     *
     * @var array[]
     * @since 1.0.0
     */
    private array $relations = [];

    /**
     * Amount of outgoing links from an element
     *
     * @var int[]
     * @since 1.0.0
     */
    private array $outgoing = [];

    /**
     * Constructor.
     *
     * @param array[] $relations Relations between elements (keys => link from, array => link to)
     * @param bool    $isUnique  Only consider unique relations
     * @param float   $damping   Damping value
     *
     * @since 1.0.0
     */
    public function __construct(array $relations, bool $isUnique = true, float $damping = 0.85)
    {
        $this->damping = $damping;

        foreach ($relations as $key => $relation) {
            $this->outgoing[$key] = \count($relation);

            if (!isset($this->relations[$key])) {
                $this->relations[$key] = [];
            }

            foreach ($relation as $linkTo) {
                if (!isset($this->relations[$linkTo])) {
                    $this->relations[$linkTo] = [];
                }

                if (!isset($this->outgoing[$linkTo])) {
                    $this->outgoing[$linkTo] = 0;
                }

                if (!$isUnique || !\in_array($key, $this->relations[$linkTo])) {
                    $this->relations[$linkTo][] = $key;
                }
            }
        }
    }

    /**
     * Calcualte the rank based on a start rank for the different elements
     *
     * A different start rank for different elements might make sense if the elements are not uniform from the very beginning
     *
     * @param int                      $iterations Algorithm iterations
     * @param null|array<mixed, float> $startRank  Start rank for an element
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function calculateRanks(int $iterations = 20, array $startRank = null) : array
    {
        if ($startRank !== null) {
            $this->pageRanks = $startRank;
        } else {
            foreach ($this->relations as $key => $relation) {
                $this->pageRanks[$key] = 0.0;
            }
        }

        for ($i = 0; $i < $iterations; ++$i) {
            foreach ($this->relations as $key => $relation) {
                $PR  = 0.0;

                foreach ($relation as $linkFrom) {
                    $PR += $this->pageRanks[$linkFrom] / $this->outgoing[$linkFrom];
                }

                $this->pageRanks[$key] = 1 - $this->damping + $this->damping * $PR;
            }
        }

        return $this->pageRanks;
    }
}

```

