# Knowledge graph design and queries

## Authors

[describe author matching]

## Relationships between works

Citation is a key relationship between publications. In LOIS this is represented using schema:citation to connect a work with the work it cites. Hence for any work we can list the articles that it cites, and the articles that it is cited by.

As well as locating the work in the scholarly network, we can make further use of citations. Two works, A and B, are co-cited if there is a work X that cites both A and B. We can take that relationship as an indication that the authors of X felt that A and B were in some way related (for example, they were on the same topic).

Making use of citation relationships is straightforward if we have citation links between works. However, in many cases such information is not readily available. Not every article in CrossRef has a list of literature cited. Furthermore, even if we do have such a list, not all of the cited works have DOIs, which means we can’t create the citation link. We can either chose to limit ourselves to existing DOI - DOI citations links (cite Shotton), or endeavour to add addition citation links. This could be by adding missing DOIs to literature cited lists (for example, if publisher was unaware of a DOI for a cited work), or by adding other identifiers that are available for the cited work.

In LOIS these additional DOIs or other identifiers are added using schema:sameAS, which means that a SPARKL query to find all  cited works needs to combine both direct schema:citation relationships and indirected schema:sameAs relationships.

[this is described in sameAs/README.md]

[example]

[need to figure out how to do this for related]








