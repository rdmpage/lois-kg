# ResearchGate problems

## Multiple JSON-LD

Now seems to return multiple JSON-LD records within same URL (sigh)

## Errors

If ResearchGate metadata is incorrect we can get wrong author assignments if we rely on author order, e.g.

https://www.researchgate.net/publication/299433692_Callicarpa_bachmaensis_Soejima_Tagane_Lamiaceae_a_new_species_from_Bach_Ma_National_Park_in_Thua_Thien_Hue_Province_Central_Vietnam

```
{
	"@context": "https:\/\/schema.org\/",
	"@type": "ScholarlyArticle",
	"datePublished": "2016-03-25",
	"headline": "Callicarpa bachmaensis Soejima & Tagane (Lamiaceae), a new species from Bach Ma National Park in Thua...",
	"mainEntityOfPage": "https:\/\/www.researchgate.net\/publication\/299433692_Callicarpa_bachmaensis_Soejima_Tagane_Lamiaceae_a_new_species_from_Bach_Ma_National_Park_in_Thua_Thien_Hue_Province_Central_Vietnam",
	"image": "https:\/\/i1.rgstatic.net\/publication\/299433692_Callicarpa_bachmaensis_Soejima_Tagane_Lamiaceae_a_new_species_from_Bach_Ma_National_Park_in_Thua_Thien_Hue_Province_Central_Vietnam\/links\/57024fbf08ae1924a7679d63\/largepreview.png",
	"author": [{
		"@context": "https:\/\/schema.org\/",
		"@type": "Person",
		"name": "Akiko Soejima",
		"url": "https:\/\/www.researchgate.net\/profile\/Akiko_Soejima",
		"image": "https:\/\/i1.rgstatic.net\/ii\/profile.image\/413022377005062-1475483616445_Q64\/Akiko_Soejima.jpg",
		"memberOf": {
			"@context": "https:\/\/schema.org\/",
			"@type": "Organization",
			"name": "Kumamoto University"
		}
	}, {
		"@context": "https:\/\/schema.org\/",
		"@type": "Person",
		"name": "Shuichiro Tagane",
		"url": "https:\/\/www.researchgate.net\/scientific-contributions\/2066683599_Shuichiro_Tagane",
		"image": "https:\/\/c5.rgstatic.net\/m\/435982309481010\/images\/template\/default\/author\/author_default_m.jpg"
	}, {
		"@context": "https:\/\/schema.org\/",
		"@type": "Person",
		"name": "Ngoc Nguyen",
		"url": "https:\/\/www.researchgate.net\/profile\/Ngoc_Nguyen52",
		"image": "https:\/\/i1.rgstatic.net\/ii\/profile.image\/493790847868929-1494740320914_Q64\/Ngoc_Nguyen52.jpg",
		"memberOf": {
			"@context": "https:\/\/schema.org\/",
			"@type": "Organization",
			"name": "University of Dalat"
		}
	}, {
		"@context": "https:\/\/schema.org\/",
		"@type": "Person",
		"name": "Tetsukazu Yahara",
		"url": "https:\/\/www.researchgate.net\/profile\/Tetsukazu_Yahara",
		"image": "https:\/\/i1.rgstatic.net\/ii\/profile.image\/277585633267721-1443192980966_Q64\/Tetsukazu_Yahara.jpg",
		"memberOf": {
			"@context": "https:\/\/schema.org\/",
			"@type": "Organization",
			"name": "Kyushu University"
		}
	}]
}
```

ResearchGate has source authors in metadata, ResearchGate web page lists 6, so breaks http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.3897/phytokeys.62.7974

