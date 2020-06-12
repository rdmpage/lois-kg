<?php

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/elastic.php');


$json = '{
    "total_rows": 84,
    "offset": 0,
    "rows": [
        {
            "id": "https://doi.org/10.1002/fedr.200711143",
            "key": "https://doi.org/10.1002/fedr.200711143",
            "value": {
                "id": "https-doi-org-10-1002-fedr-200711143",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Two new species and one new subspecies ofAspidistraKer-Gawl. (Ruscaceae) from Vietnam",
                    "url": "https://doi.org/10.1002/fedr.200711143"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1002/fedr.200711143",
                    "type": "ScholarlyArticle",
                    "doi": "10.1002/fedr.200711143",
                    "fulltext": "Two new species and one new subspecies ofAspidistraKer-Gawl. (Ruscaceae) from Vietnam 10.1002/fedr.200711143 H.-J. Tillich L. V. Averyanov 119 1-2 37 41",
                    "fulltext_boosted": "Two new species and one new subspecies ofAspidistraKer-Gawl. (Ruscaceae) from Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1002/fedr.201800002",
            "key": "https://doi.org/10.1002/fedr.201800002",
            "value": {
                "id": "https-doi-org-10-1002-fedr-201800002",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "A critical survey of infraspecific taxa in the genus Aspidistra\n (Asparagaceae)",
                    "url": "https://doi.org/10.1002/fedr.201800002"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1002/fedr.201800002",
                    "type": "ScholarlyArticle",
                    "doi": "10.1002/fedr.201800002",
                    "fulltext": "A critical survey of infraspecific taxa in the genus Aspidistra\n (Asparagaceae) 10.1002/fedr.201800002 Hans-Jürgen Tillich Leonid. V. Averyanov 129 3 185 188",
                    "fulltext_boosted": "A critical survey of infraspecific taxa in the genus Aspidistra\n (Asparagaceae)"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/j.1756-1051.2012.01588.x",
            "key": "https://doi.org/10.1111/j.1756-1051.2012.01588.x",
            "value": {
                "id": "https-doi-org-10-1111-j-1756-1051-2012-01588-x",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Hoya longipedunculatasp. nov. (Apocynaceae, Asclepiadoideae) from Quang Nam, central Vietnam",
                    "url": "https://doi.org/10.1111/j.1756-1051.2012.01588.x"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/j.1756-1051.2012.01588.x",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/j.1756-1051.2012.01588.x",
                    "fulltext": "Hoya longipedunculatasp. nov. (Apocynaceae, Asclepiadoideae) from Quang Nam, central Vietnam 10.1111/j.1756-1051.2012.01588.x Van The Pham Leonid V. Averyanov 30 6 705 708",
                    "fulltext_boosted": "Hoya longipedunculatasp. nov. (Apocynaceae, Asclepiadoideae) from Quang Nam, central Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/j.1756-1051.2013.00273.x",
            "key": "https://doi.org/10.1111/j.1756-1051.2013.00273.x",
            "value": {
                "id": "https-doi-org-10-1111-j-1756-1051-2013-00273-x",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra albopurpurea, A. khangii, A. lubaeandA. stellataspp. nov. (Asparagaceae, Convallariaceae s.s.) from Indochina",
                    "url": "https://doi.org/10.1111/j.1756-1051.2013.00273.x"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/j.1756-1051.2013.00273.x",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/j.1756-1051.2013.00273.x",
                    "fulltext": "Aspidistra albopurpurea, A. khangii, A. lubaeandA. stellataspp. nov. (Asparagaceae, Convallariaceae s.s.) from Indochina 10.1111/j.1756-1051.2013.00273.x Leonid V. Averyanov H.-J. Tillich 32 6 752 760",
                    "fulltext_boosted": "Aspidistra albopurpurea, A. khangii, A. lubaeandA. stellataspp. nov. (Asparagaceae, Convallariaceae s.s.) from Indochina"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/j.1756-1051.2013.00304.x",
            "key": "https://doi.org/10.1111/j.1756-1051.2013.00304.x",
            "value": {
                "id": "https-doi-org-10-1111-j-1756-1051-2013-00304-x",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Trachycarpus raveniisp. nov. (Arecaceae, Corypheae) from central Laos",
                    "url": "https://doi.org/10.1111/j.1756-1051.2013.00304.x"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/j.1756-1051.2013.00304.x",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/j.1756-1051.2013.00304.x",
                    "fulltext": "Trachycarpus raveniisp. nov. (Arecaceae, Corypheae) from central Laos 10.1111/j.1756-1051.2013.00304.x The Van Pham Leonid V. Averyanov Shengvilai Lorphengsy Khang Sinh Nguyen Tien Hiep Nguyen 32 5 563 568",
                    "fulltext_boosted": "Trachycarpus raveniisp. nov. (Arecaceae, Corypheae) from central Laos"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.00498",
            "key": "https://doi.org/10.1111/njb.00498",
            "value": {
                "id": "https-doi-org-10-1111-njb-00498",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Gymnosperms of Laos",
                    "url": "https://doi.org/10.1111/njb.00498"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.00498",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.00498",
                    "fulltext": "Gymnosperms of Laos 10.1111/njb.00498 Khamfa Chantthavongsa Leonid V. Averyanov Tien Hiep Nguyen Khang Nguyen Sinh The Van Pham Vichith Lamxay Somchanh Bounphanmy Shengvilai Lorphengsy Loc Ke Phan Soulivanh Lanorsavanh 32 6 765 805",
                    "fulltext_boosted": "Gymnosperms of Laos"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.00541",
            "key": "https://doi.org/10.1111/njb.00541",
            "value": {
                "id": "https-doi-org-10-1111-njb-00541",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Hoya hanhiaesp. nov. (Apocynaceae, Asclepiadoideae) from central Vietnam",
                    "url": "https://doi.org/10.1111/njb.00541"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.00541",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.00541",
                    "fulltext": "Hoya hanhiaesp. nov. (Apocynaceae, Asclepiadoideae) from central Vietnam 10.1111/njb.00541 Van The Pham Tuan Anh Le Leonid V. Averyanov 33 1 64 67",
                    "fulltext_boosted": "Hoya hanhiaesp. nov. (Apocynaceae, Asclepiadoideae) from central Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.00664",
            "key": "https://doi.org/10.1111/njb.00664",
            "value": {
                "id": "https-doi-org-10-1111-njb-00664",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra laotica, A. multiflora, A. ovifloraandA. semiapertaspp. nov. (Asparagaceae, Convallariaceae s.s.) from eastern Indochina",
                    "url": "https://doi.org/10.1111/njb.00664"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.00664",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.00664",
                    "fulltext": "Aspidistra laotica, A. multiflora, A. ovifloraandA. semiapertaspp. nov. (Asparagaceae, Convallariaceae s.s.) from eastern Indochina 10.1111/njb.00664 Leonid V. Averyanov H.-J. Tillich 33 3 366 376",
                    "fulltext_boosted": "Aspidistra laotica, A. multiflora, A. ovifloraandA. semiapertaspp. nov. (Asparagaceae, Convallariaceae s.s.) from eastern Indochina"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.00664#creator-2",
            "key": "https://doi.org/10.1111/njb.00664#creator-2",
            "value": {
                "id": "https-doi-org-10-1111-njb-00664-creator-2",
                "type": "Person",
                "search_result_data": {
                    "name": "H.-J. Tillich",
                    "url": "https://doi.org/10.1111/njb.00664#creator-2"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:38863-1",
                    "type": "Person",
                    "fulltext": "H.-J. Tillich",
                    "fulltext_boosted": "H.-J. Tillich"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.01249",
            "key": "https://doi.org/10.1111/njb.01249",
            "value": {
                "id": "https-doi-org-10-1111-njb-01249",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Notes on taxonomy and new taxa ofAspidistra(Ruscaceae) in the flora of Laos and Vietnam",
                    "url": "https://doi.org/10.1111/njb.01249"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.01249",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.01249",
                    "fulltext": "Notes on taxonomy and new taxa ofAspidistra(Ruscaceae) in the flora of Laos and Vietnam 10.1111/njb.01249 Leonid V. Averyanov H.-J. Tillich 35 1 48 57",
                    "fulltext_boosted": "Notes on taxonomy and new taxa ofAspidistra(Ruscaceae) in the flora of Laos and Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.01504",
            "key": "https://doi.org/10.1111/njb.01504",
            "value": {
                "id": "https-doi-org-10-1111-njb-01504",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra cyathiflora\n var. bifolia\n and A. neglecta\n spp. nov. (Convallariaceae) from northern Vietnam",
                    "url": "https://doi.org/10.1111/njb.01504"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.01504",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.01504",
                    "fulltext": "Aspidistra cyathiflora\n var. bifolia\n and A. neglecta\n spp. nov. (Convallariaceae) from northern Vietnam 10.1111/njb.01504 Ly Ngoc Sam H.-J. Tillich Khang Sinh Nguyen Van The Pham Leonid V. Averyanov Tatiana V. Maisak 35 4 482 487",
                    "fulltext_boosted": "Aspidistra cyathiflora\n var. bifolia\n and A. neglecta\n spp. nov. (Convallariaceae) from northern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.01660",
            "key": "https://doi.org/10.1111/njb.01660",
            "value": {
                "id": "https-doi-org-10-1111-njb-01660",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New species of Peliosanthes, Rohdea\n and Tupistra\n (Asparagaceae) from Laos and Vietnam",
                    "url": "https://doi.org/10.1111/njb.01660"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.01660",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.01660",
                    "fulltext": "New species of Peliosanthes, Rohdea\n and Tupistra\n (Asparagaceae) from Laos and Vietnam 10.1111/njb.01660 Tien Hiep Nguyen Leonid V. Averyanov Noriyuki Tanaka Khang Sinh Nguyen Quynh Nga Nguyen Tatiana V. Maisak 35 6 697 710",
                    "fulltext_boosted": "New species of Peliosanthes, Rohdea\n and Tupistra\n (Asparagaceae) from Laos and Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.01833",
            "key": "https://doi.org/10.1111/njb.01833",
            "value": {
                "id": "https-doi-org-10-1111-njb-01833",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New taxa and taxonomic notes in Aspidistra\n (Convallariaceae s.s.) in China, Laos and Vietnam",
                    "url": "https://doi.org/10.1111/njb.01833"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.01833",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.01833",
                    "fulltext": "New taxa and taxonomic notes in Aspidistra\n (Convallariaceae s.s.) in China, Laos and Vietnam 10.1111/njb.01833 H.-J. Tillich Leonid V. Averyanov khang Sinh Nguyen Van The Pham Quang Cuong Truong Thi Lien Thuong Nguyen Tien Chinh Vu Sinh Khang Nguyen Tuan Anh Le Hoang Tuan Nguyen Tatiana V. Maisak Anh Hoang Le Tuan Danh Duc Nguyen 36 7 e01833",
                    "fulltext_boosted": "New taxa and taxonomic notes in Aspidistra\n (Convallariaceae s.s.) in China, Laos and Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.1111/njb.01883",
            "key": "https://doi.org/10.1111/njb.01883",
            "value": {
                "id": "https-doi-org-10-1111-njb-01883",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Silvorchis vietnamica(Orchidaceae, Orchidoideae, Vietorchidinae), a new miniature mycotrophic species from southern Vietnam",
                    "url": "https://doi.org/10.1111/njb.01883"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.1111/njb.01883",
                    "type": "ScholarlyArticle",
                    "doi": "10.1111/njb.01883",
                    "fulltext": "Silvorchis vietnamica(Orchidaceae, Orchidoideae, Vietorchidinae), a new miniature mycotrophic species from southern Vietnam 10.1111/njb.01883 Leonid V. Averyanov Van Dzu Nguyen Khang Sinh Nguyen Quang Diep Dinh Tatiana V. Maisak 36 7 e01883",
                    "fulltext_boosted": "Silvorchis vietnamica(Orchidaceae, Orchidoideae, Vietorchidinae), a new miniature mycotrophic species from southern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.164.2.3",
            "key": "https://doi.org/10.11646/phytotaxa.164.2.3",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-164-2-3",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Gastrochilus kadooriei (Orchidaceae), a new species from Hong Kong, with notes on allied taxa in section Microphyllae found in the region.",
                    "url": "https://doi.org/10.11646/phytotaxa.164.2.3",
                    "description": "<p>A new species, Gastrochilus kadooriei, is described from Hong Kong. Notes are presented on its distribution, ecology and conservation status, and its distinguishing features are compared with those of allied taxa. Gastrochilus jeitouensis is reduced to the synonymy of G. distichus, and a lectotype is assigned for G. pseudodistichus. Gastrochilus fuscopunctatus is reinstated as an accepted species. Dichotomous keys to this taxonomically difficult group of morphologically similar species are presented.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.164.2.3",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.164.2.3",
                    "fulltext": "Gastrochilus kadooriei (Orchidaceae), a new species from Hong Kong, with notes on allied taxa in section Microphyllae found in the region. <p>A new species, Gastrochilus kadooriei, is described from Hong Kong. Notes are presented on its distribution, ecology and conservation status, and its distinguishing features are compared with those of allied taxa. Gastrochilus jeitouensis is reduced to the synonymy of G. distichus, and a lectotype is assigned for G. pseudodistichus. Gastrochilus fuscopunctatus is reinstated as an accepted species. Dichotomous keys to this taxonomically difficult group of morphologically similar species are presented.</p> 10.11646/phytotaxa.164.2.3 Pankaj Kumar Stephan W. Gale Alexander Kocyan Gunter A. Fischer Leonid Averyanov Renata Borosova Avishek Bhattacharjee Ji-Hong Li Kuen Shum Pang 164 2 91",
                    "fulltext_boosted": "Gastrochilus kadooriei (Orchidaceae), a new species from Hong Kong, with notes on allied taxa in section Microphyllae found in the region."
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.175.5.8",
            "key": "https://doi.org/10.11646/phytotaxa.175.5.8",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-175-5-8",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Tupistra khangii (Asparagaceae), a new species from northern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.175.5.8",
                    "description": "<p>Tupistra khangii (Asparagaceae) is described and illustrated as a new species from mountain areas in northern Vietnam. It is distributed widely in north-western Vietnam and adjacent territories.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.175.5.8",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.175.5.8",
                    "fulltext": "Tupistra khangii (Asparagaceae), a new species from northern Vietnam <p>Tupistra khangii (Asparagaceae) is described and illustrated as a new species from mountain areas in northern Vietnam. It is distributed widely in north-western Vietnam and adjacent territories.</p> 10.11646/phytotaxa.175.5.8 NIKOLAY A. VISLOBOKOV ANDREY N. KUZNETSOV NORIYUKI ТANAKA LEONID V. AVERYANOV HIEP TIEN NGUYEN MAXIM S. NURALIEV 175 5 287",
                    "fulltext_boosted": "Tupistra khangii (Asparagaceae), a new species from northern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.213.2.4",
            "key": "https://doi.org/10.11646/phytotaxa.213.2.4",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-213-2-4",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Bulbophyllum bidoupense and Schoenorchis hangianae—new species of orchids (Orchidaceae) from southern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.213.2.4",
                    "description": "<p>Bulbophyllum bidoupense (sect. Brachystachyae) and Schoenorchis hangianae (sect. Pumila) are described and illustrated as species new for science. Both species are local endemics of the Bidoup mountain system belonging to the South Annamese floristic province of the Indochinese floristic region within Lam Dong and Khanh Hoa provinces of southern Vietnam. Schoenorchis hangianae and allied S. scolopendria are rather isolated species recognized among their congeners by plagiotropic creeping plant habit.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.213.2.4",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.213.2.4",
                    "fulltext": "Bulbophyllum bidoupense and Schoenorchis hangianae—new species of orchids (Orchidaceae) from southern Vietnam <p>Bulbophyllum bidoupense (sect. Brachystachyae) and Schoenorchis hangianae (sect. Pumila) are described and illustrated as species new for science. Both species are local endemics of the Bidoup mountain system belonging to the South Annamese floristic province of the Indochinese floristic region within Lam Dong and Khanh Hoa provinces of southern Vietnam. Schoenorchis hangianae and allied S. scolopendria are rather isolated species recognized among their congeners by plagiotropic creeping plant habit.</p> 10.11646/phytotaxa.213.2.4 Duy Van Nong Leonid V. Averyanov 213 2 113",
                    "fulltext_boosted": "Bulbophyllum bidoupense and Schoenorchis hangianae—new species of orchids (Orchidaceae) from southern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.238.2.2",
            "key": "https://doi.org/10.11646/phytotaxa.238.2.2",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-238-2-2",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Four new species of orchids (Orchidaceae) in eastern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.238.2.2",
                    "description": "<p>Dendrobium thinhii (D. sect. Breviflores), Sarcoglyphis tichii, Taeniophyllum phitamii (T. subgen. Codonosepalum Schltr.) and Trichoglottis canhii are described and illustrated as species new to science. All are local endemics of the area associated with Truong Son Range (Annamese Cordilleras) within territories of Dac Lak, Kon Tum and Lam Dong provinces of the southern Vietnam known in national geography as the Central Highlands or Tay Nguyen Plateau. All discovered plants are well-defined, taxonomically isolated species representing very strict plant endemism quite typical for the southern part of eastern Indochina.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.238.2.2",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.238.2.2",
                    "fulltext": "Four new species of orchids (Orchidaceae) in eastern Vietnam <p>Dendrobium thinhii (D. sect. Breviflores), Sarcoglyphis tichii, Taeniophyllum phitamii (T. subgen. Codonosepalum Schltr.) and Trichoglottis canhii are described and illustrated as species new to science. All are local endemics of the area associated with Truong Son Range (Annamese Cordilleras) within territories of Dac Lak, Kon Tum and Lam Dong provinces of the southern Vietnam known in national geography as the Central Highlands or Tay Nguyen Plateau. All discovered plants are well-defined, taxonomically isolated species representing very strict plant endemism quite typical for the southern part of eastern Indochina.</p> 10.11646/phytotaxa.238.2.2 Leonid V. Averyanov NONG VAN DUY TRAN THAI VINH QUACH VAN HOI VU KIM CONG 238 2 136",
                    "fulltext_boosted": "Four new species of orchids (Orchidaceae) in eastern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.244.3.2",
            "key": "https://doi.org/10.11646/phytotaxa.244.3.2",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-244-3-2",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Xyloselinum laoticum (Umbelliferae), a new species from Laos, and taxonomic placement of the genus in the light of nrDNA ITS sequence analysis",
                    "url": "https://doi.org/10.11646/phytotaxa.244.3.2",
                    "description": "<p>A new species of Xyloselinum, X. laoticum, endemic to the Vientiane province of Laos, is described and illustrated. Similar to two previously described species of Xyloselinum from limestone ridges of Northern Vietnam, the new species is subshrub with 2–3 pinnatisect leaves having petiolulate basal segments and broadly lanceolate terminal segments, solitary or binary mericarp vallecular vittae, almost flat on the commissural side endosperm. The new species is more closer to X. vietnamense than to X. leonidii. Xyloselinum laoticum differs from X. vietnamense in irregularly toothed or laciniate terminal leaf segments without bladders on lower surface, solid (not fistulose) petioles, globose umbels and umbellets, almost glabrous (not densely covered by scattered short prickles) pedicels, always entire bracteoles, and conical (not shortly conical) stylopods. It is supported by the nrDNA ITS data that Xyloselinum is treated as an independent genus and placed in Selineae Clade together with some S and E Asian genera separated from Peucedanum s.l. or Ligusticum/Selinum complex.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.244.3.2",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.244.3.2",
                    "fulltext": "Xyloselinum laoticum (Umbelliferae), a new species from Laos, and taxonomic placement of the genus in the light of nrDNA ITS sequence analysis <p>A new species of Xyloselinum, X. laoticum, endemic to the Vientiane province of Laos, is described and illustrated. Similar to two previously described species of Xyloselinum from limestone ridges of Northern Vietnam, the new species is subshrub with 2–3 pinnatisect leaves having petiolulate basal segments and broadly lanceolate terminal segments, solitary or binary mericarp vallecular vittae, almost flat on the commissural side endosperm. The new species is more closer to X. vietnamense than to X. leonidii. Xyloselinum laoticum differs from X. vietnamense in irregularly toothed or laciniate terminal leaf segments without bladders on lower surface, solid (not fistulose) petioles, globose umbels and umbellets, almost glabrous (not densely covered by scattered short prickles) pedicels, always entire bracteoles, and conical (not shortly conical) stylopods. It is supported by the nrDNA ITS data that Xyloselinum is treated as an independent genus and placed in Selineae Clade together with some S and E Asian genera separated from Peucedanum s.l. or Ligusticum/Selinum complex.</p> 10.11646/phytotaxa.244.3.2 Michael G Pimenov Galina V. Degtjareva Tatiana A. Ostroumova Tahir H. Samigullin Leonid V. Averyanov 244 3 248",
                    "fulltext_boosted": "Xyloselinum laoticum (Umbelliferae), a new species from Laos, and taxonomic placement of the genus in the light of nrDNA ITS sequence analysis"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.305.1.8",
            "key": "https://doi.org/10.11646/phytotaxa.305.1.8",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-305-1-8",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Tupistra ashihoi (Asparagaceae), a new species from north-eastern India",
                    "url": "https://doi.org/10.11646/phytotaxa.305.1.8",
                    "description": "<p>Tupistra Ker Gawler (1814: 1655) belonging to Asparagaceae subfamily Nolinoideae (APG 2009, Chase et al. 2009), includes about 26 species (Govaerts 2016). These taxa spread mainly in south and south-east of continental Asia, including Nepal, Bhutan, India, Myanmar, China, Laos, Vietnam, Thailand and Malaysia (Tanaka 2003a, 2003b,  2010a, 2010b, Averyanov et al. 2016). This genus is characterised by leaves with slender petiolar base, relatively large stigma broader than the style, stout columnar style almost as thick as the ovary and usually tuberculate, dirty green, globular berry-like fruit (Tanaka 2003a, 2010a). In India, Tupistra is represented so far by four species, namely Tupistra clarkei Hooker (1894: 325), T. nutans Wall. ex Lindley (1839: 1223), T. stoliczana Kurz (1875: 199) and T. tupistroides (Kunth 1848: 12) Dandy (1932: 329).</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.305.1.8",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.305.1.8",
                    "fulltext": "Tupistra ashihoi (Asparagaceae), a new species from north-eastern India <p>Tupistra Ker Gawler (1814: 1655) belonging to Asparagaceae subfamily Nolinoideae (APG 2009, Chase et al. 2009), includes about 26 species (Govaerts 2016). These taxa spread mainly in south and south-east of continental Asia, including Nepal, Bhutan, India, Myanmar, China, Laos, Vietnam, Thailand and Malaysia (Tanaka 2003a, 2003b,  2010a, 2010b, Averyanov et al. 2016). This genus is characterised by leaves with slender petiolar base, relatively large stigma broader than the style, stout columnar style almost as thick as the ovary and usually tuberculate, dirty green, globular berry-like fruit (Tanaka 2003a, 2010a). In India, Tupistra is represented so far by four species, namely Tupistra clarkei Hooker (1894: 325), T. nutans Wall. ex Lindley (1839: 1223), T. stoliczana Kurz (1875: 199) and T. tupistroides (Kunth 1848: 12) Dandy (1932: 329).</p> 10.11646/phytotaxa.305.1.8 LEONID V. AVERYANOV DILIP KR. ROY N. ODYUO 305 1 52",
                    "fulltext_boosted": "Tupistra ashihoi (Asparagaceae), a new species from north-eastern India"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.308.1.14",
            "key": "https://doi.org/10.11646/phytotaxa.308.1.14",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-308-1-14",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra letreae (Asparagaceae), a new species from central Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.308.1.14",
                    "description": "<p>Since last account of the genus Aspidistra Ker Gawler (1822: 628) in Vietnam reported 43 species (Tillich 2014), at least 25 additional species were discovered and described in this country (Averyanov &amp; Tillich 2014, 2015, 2016, Vislobokov et al. 2014a, 2014b, 2016, Leong-Skornickova et al. 2014, Olivier 2015, Ly &amp; Tilllich 2015, Vislobokov 2015, 2016, Averyanov et al. 2016). Meanwhile, the diversity of the genus in Vietnam and allied countries remains insufficiently inventoried. One more new species of Aspidistra, named here as A. letreae was discovered recently in central Vietnam. This species is described and illustrated with data on its ecology, phenology, tentative relations, distribution and expected conservation status. The new species somewhat resembles Aspidistra truongii Averyanov &amp; Tillich (2013: 108), A. obtusata Vislobokov (2016: 694) and Aspidistra zhangii Averyanov, Tillich &amp; Nguyen in Averyanov et al. (2016: 62), but differs clearly by a series of  morphological features noted below.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.308.1.14",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.308.1.14",
                    "fulltext": "Aspidistra letreae (Asparagaceae), a new species from central Vietnam <p>Since last account of the genus Aspidistra Ker Gawler (1822: 628) in Vietnam reported 43 species (Tillich 2014), at least 25 additional species were discovered and described in this country (Averyanov &amp; Tillich 2014, 2015, 2016, Vislobokov et al. 2014a, 2014b, 2016, Leong-Skornickova et al. 2014, Olivier 2015, Ly &amp; Tilllich 2015, Vislobokov 2015, 2016, Averyanov et al. 2016). Meanwhile, the diversity of the genus in Vietnam and allied countries remains insufficiently inventoried. One more new species of Aspidistra, named here as A. letreae was discovered recently in central Vietnam. This species is described and illustrated with data on its ecology, phenology, tentative relations, distribution and expected conservation status. The new species somewhat resembles Aspidistra truongii Averyanov &amp; Tillich (2013: 108), A. obtusata Vislobokov (2016: 694) and Aspidistra zhangii Averyanov, Tillich &amp; Nguyen in Averyanov et al. (2016: 62), but differs clearly by a series of  morphological features noted below.</p> 10.11646/phytotaxa.308.1.14 TATIANA V. MAISAK VAN THE PHAM TIEN CHINH VU LEONID V. AVERYANOV HANS-JUERGEN TILLICH TUAN ANH LE 308 1 137",
                    "fulltext_boosted": "Aspidistra letreae (Asparagaceae), a new species from central Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.308.1.9",
            "key": "https://doi.org/10.11646/phytotaxa.308.1.9",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-308-1-9",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Disanthus ovatifolius (Hamamelidaceae), a new species from northwestern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.308.1.9",
                    "description": "<p>Disanthus ovatifolius discovered in northwestern Vietnam is described as a new species of Hamamelidaceae, subfamily Disanthoideae. The new species belongs to the genus Disanthus, which was represented only by the type species of the genus, D. cercidifolius, until now. The new species differs from its congener in a series of morphological characters, such as the evergreen narrowly ovate leaves and cornute fruits. Detailed analytical color plate and ink drawing are provided for the new species along with data on its ecology, phenology and distribution. Similar plants were introduced into European horticulture under the invalid name Uocodendron whartonii hort. since 2006.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.308.1.9",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.308.1.9",
                    "fulltext": "Disanthus ovatifolius (Hamamelidaceae), a new species from northwestern Vietnam <p>Disanthus ovatifolius discovered in northwestern Vietnam is described as a new species of Hamamelidaceae, subfamily Disanthoideae. The new species belongs to the genus Disanthus, which was represented only by the type species of the genus, D. cercidifolius, until now. The new species differs from its congener in a series of morphological characters, such as the evergreen narrowly ovate leaves and cornute fruits. Detailed analytical color plate and ink drawing are provided for the new species along with data on its ecology, phenology and distribution. Similar plants were introduced into European horticulture under the invalid name Uocodendron whartonii hort. since 2006.</p> 10.11646/phytotaxa.308.1.9 LEONID V. AVERYANOV PETER K. ENDRESS BUI HONG QUANG KHANG SINH NGUYEN DZU VAN NGUYEN 308 1 104",
                    "fulltext_boosted": "Disanthus ovatifolius (Hamamelidaceae), a new species from northwestern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.309.3.11",
            "key": "https://doi.org/10.11646/phytotaxa.309.3.11",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-309-3-11",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Rohdea extrorsandra (Asparagaceae), a new species from north-eastern India",
                    "url": "https://doi.org/10.11646/phytotaxa.309.3.11",
                    "description": "<p>The genus Rohdea Roth (1821: 196) belonging to the family Asparagaceae (APG 2009) comprises 14 species and is distributed in South East Asia (Tanaka 2003, Averyanov et al. 2014, Govaerts 2016). In India, the genus includes 4 species namely R. delavayi (Franchet 1896: 40) Tanaka (2003: 331), R. eucomoides (Baker 1875: 581) Tanaka (2003: 332), R. nepalensis (Rafinesque 1838: 15) Tanaka (2010: 23) and R. wattii (Clarke 1889: 78) Yamashita &amp; Tamura (2004: 369) (Hooker 1894, Liang &amp; Tamura 2000).</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.309.3.11",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.309.3.11",
                    "fulltext": "Rohdea extrorsandra (Asparagaceae), a new species from north-eastern India <p>The genus Rohdea Roth (1821: 196) belonging to the family Asparagaceae (APG 2009) comprises 14 species and is distributed in South East Asia (Tanaka 2003, Averyanov et al. 2014, Govaerts 2016). In India, the genus includes 4 species namely R. delavayi (Franchet 1896: 40) Tanaka (2003: 331), R. eucomoides (Baker 1875: 581) Tanaka (2003: 332), R. nepalensis (Rafinesque 1838: 15) Tanaka (2010: 23) and R. wattii (Clarke 1889: 78) Yamashita &amp; Tamura (2004: 369) (Hooker 1894, Liang &amp; Tamura 2000).</p> 10.11646/phytotaxa.309.3.11 N. ODYUO DILIP KR. ROY LEONID V. AVERYANOV 309 3 283",
                    "fulltext_boosted": "Rohdea extrorsandra (Asparagaceae), a new species from north-eastern India"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.312.1.11#ref31",
            "key": "https://doi.org/10.11646/phytotaxa.312.1.11#ref31",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-312-1-11-ref31",
                "type": "CreativeWork",
                "search_result_data": {
                    "name": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.312.1.11#ref31",
                    "description": "Tillich, H.-J. (2014) The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam. Taiwania 59: 1–8."
                },
                "search_data": {
                    "cluster_id": "urn:sici:0372-333X(2014)59%3C1:TGAKAI%3E2.0.CO;2-W",
                    "type": "CreativeWork",
                    "fulltext": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam Tillich, H.-J. (2014) The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam. Taiwania 59: 1–8. 59 1",
                    "fulltext_boosted": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.312.1.16#ref10",
            "key": "https://doi.org/10.11646/phytotaxa.312.1.16#ref10",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-312-1-16-ref10",
                "type": "CreativeWork",
                "search_result_data": {
                    "name": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.312.1.16#ref10",
                    "description": "Tillich, H.J. (2014) The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam. Taiwania 59: 1–8."
                },
                "search_data": {
                    "cluster_id": "urn:sici:0372-333X(2014)59%3C1:TGAKAI%3E2.0.CO;2-W",
                    "type": "CreativeWork",
                    "fulltext": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam Tillich, H.J. (2014) The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam. Taiwania 59: 1–8. 59 1",
                    "fulltext_boosted": "The genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.312.2.3",
            "key": "https://doi.org/10.11646/phytotaxa.312.2.3",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-312-2-3",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New taxa of Peliosanthes and Tupistra (Asparagaceae) in the flora of Laos and Vietnam and supplemental data for T. patula",
                    "url": "https://doi.org/10.11646/phytotaxa.312.2.3",
                    "description": "<p>Three new taxa, Tupistra gracilis, Peliosanthes griffithii var. breviracemosa and P. hirsuta, are described and illustrated. The first two taxa are local endemics of northern Vietnam and the last species is endemic to karstic limestone areas of central Laos. Tupistra fungilliformis and P. yunnanensis are recorded for the first time for Vietnam. A recently described species T. patula from northern Vietnam is supplemented with new data on its morphology, ecology and distribution.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.312.2.3",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.312.2.3",
                    "fulltext": "New taxa of Peliosanthes and Tupistra (Asparagaceae) in the flora of Laos and Vietnam and supplemental data for T. patula <p>Three new taxa, Tupistra gracilis, Peliosanthes griffithii var. breviracemosa and P. hirsuta, are described and illustrated. The first two taxa are local endemics of northern Vietnam and the last species is endemic to karstic limestone areas of central Laos. Tupistra fungilliformis and P. yunnanensis are recorded for the first time for Vietnam. A recently described species T. patula from northern Vietnam is supplemented with new data on its morphology, ecology and distribution.</p> 10.11646/phytotaxa.312.2.3 KHANG SINH NGUYEN khang Sinh Nguyen LEONID V. AVERYANOV NORIYUKI ТANAKA EUGENE L. KONSTANTINOV TATIANA V. MAISAK HIEP TIEN NGUYEN 312 2 199",
                    "fulltext_boosted": "New taxa of Peliosanthes and Tupistra (Asparagaceae) in the flora of Laos and Vietnam and supplemental data for T. patula"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.326.4.7",
            "key": "https://doi.org/10.11646/phytotaxa.326.4.7",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-326-4-7",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Gleadovia konyakianorum (Orobanchaceae), a new species from Nagaland, India",
                    "url": "https://doi.org/10.11646/phytotaxa.326.4.7",
                    "description": "<p>A new species of the genus Gleadovia from Nagaland, Northeastern India, Gleadovia konyakianorum is here described and illustrated. The new species differs from its presently known congeners, such as G. banerjiana, G. mupinense and G. ruborum in having strictly 1-flowered inflorescence borne at stem apex, urceolate calyx unequally 5-lobed at apical part, white corolla, narrowly ovoid to fusiform, ca. 1 cm long anther, moderately shorter style, 0.5–0.6 cm long and in narrowly subulate, comparatively longer stigma, to 2.5 cm long. Identification key to the species of Gleadovia is given.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.326.4.7",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.326.4.7",
                    "fulltext": "Gleadovia konyakianorum (Orobanchaceae), a new species from Nagaland, India <p>A new species of the genus Gleadovia from Nagaland, Northeastern India, Gleadovia konyakianorum is here described and illustrated. The new species differs from its presently known congeners, such as G. banerjiana, G. mupinense and G. ruborum in having strictly 1-flowered inflorescence borne at stem apex, urceolate calyx unequally 5-lobed at apical part, white corolla, narrowly ovoid to fusiform, ca. 1 cm long anther, moderately shorter style, 0.5–0.6 cm long and in narrowly subulate, comparatively longer stigma, to 2.5 cm long. Identification key to the species of Gleadovia is given.</p> 10.11646/phytotaxa.326.4.7 N. ODYUO DILIP KR. ROY LEONID V. AVERYANOV 326 4 274",
                    "fulltext_boosted": "Gleadovia konyakianorum (Orobanchaceae), a new species from Nagaland, India"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.334.1.9",
            "key": "https://doi.org/10.11646/phytotaxa.334.1.9",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-334-1-9",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Tupistra cardinalis (Asparagaceae), a new species from limestone areas in northern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.334.1.9",
                    "description": "<p>Tupistra Ker Gawler (1814: 1655), which was later recircumscribed by Tanaka (2003a, 2010a), belongs to Nolinoideae of Asparagaceae (APG 2009, Chase et al. 2009). It comprises about 30 species (Tanaka 2010a, Averyanov et al. 2017, Govaerts 2017, Nguyen et al. 2017, Roy et al. 2017, Tanaka et al. 2017) distributed widely over subtropical to tropical Asia, covering Bangladesh, Bhutan, China, India, Indonesia, Laos, Malaysia, Myanmar, Nepal, Thailand, and Vietnam (Tanaka 2010a, Averyanov &amp; Tanaka 2012, Hu et al. 2013, Vislobokov et al. 2014, Averyanov et al. 2015, 2016, 2017, Nguyen et al. 2017, Roy et al. 2017).</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.334.1.9",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.334.1.9",
                    "fulltext": "Tupistra cardinalis (Asparagaceae), a new species from limestone areas in northern Vietnam <p>Tupistra Ker Gawler (1814: 1655), which was later recircumscribed by Tanaka (2003a, 2010a), belongs to Nolinoideae of Asparagaceae (APG 2009, Chase et al. 2009). It comprises about 30 species (Tanaka 2010a, Averyanov et al. 2017, Govaerts 2017, Nguyen et al. 2017, Roy et al. 2017, Tanaka et al. 2017) distributed widely over subtropical to tropical Asia, covering Bangladesh, Bhutan, China, India, Indonesia, Laos, Malaysia, Myanmar, Nepal, Thailand, and Vietnam (Tanaka 2010a, Averyanov &amp; Tanaka 2012, Hu et al. 2013, Vislobokov et al. 2014, Averyanov et al. 2015, 2016, 2017, Nguyen et al. 2017, Roy et al. 2017).</p> 10.11646/phytotaxa.334.1.9 HOANG THANH SON LEONID V. AVERYANOV KHANG SINH NGUYEN NORIYUKI TANAKA TATIANA V. MAISAK TIEN HIEP NGUYEN CHING-I PENG 334 1 60",
                    "fulltext_boosted": "Tupistra cardinalis (Asparagaceae), a new species from limestone areas in northern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.364.2.8",
            "key": "https://doi.org/10.11646/phytotaxa.364.2.8",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-364-2-8",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra bella (Asparagaceae), a new species from northern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.364.2.8",
                    "description": "<p>This paper continues the publication of newly obtained results of a successive taxonomic investigation of the genus Aspidistra Ker Gawler (1822: 628) in Vietnam summarized in a series of recent publications (Averyanov &amp; Tillich 2016, Averyanov et al. 2016, 2017, 2018, Vislobokov 2016, Vislobokov et al. 2016, 2017, Nguyen 2017, Nuraliev et al. 2017). A new recently discovered species is described and illustrated below.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.364.2.8",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.364.2.8",
                    "fulltext": "Aspidistra bella (Asparagaceae), a new species from northern Vietnam <p>This paper continues the publication of newly obtained results of a successive taxonomic investigation of the genus Aspidistra Ker Gawler (1822: 628) in Vietnam summarized in a series of recent publications (Averyanov &amp; Tillich 2016, Averyanov et al. 2016, 2017, 2018, Vislobokov 2016, Vislobokov et al. 2016, 2017, Nguyen 2017, Nuraliev et al. 2017). A new recently discovered species is described and illustrated below.</p> 10.11646/phytotaxa.364.2.8 LEONID V. AVERYANOV HANS JÜRGEN TILLICH KHANG SINH NGUYEN TATIANA V MAISAK 364 2 205",
                    "fulltext_boosted": "Aspidistra bella (Asparagaceae), a new species from northern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.369.1.1",
            "key": "https://doi.org/10.11646/phytotaxa.369.1.1",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-369-1-1",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New species of Bulbophyllum (Orchidaceae) in the flora of Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.369.1.1",
                    "description": "<p>Three taxa, Bulbophyllum cariniflorum var. orlovii (sect. Pleiophyllum), B. sonii (sect. Anisopetalon) and B. ustulata (sect. Brachystachya) are described as new for science. All of these novelties are local endemics of Vietnam. Additionally, four species, B. flavescens (sect. Aphanobulbon), B. ovatum (sect. Desmosanthes), B. physocoryphum (sect. Macrocaulia) and B. wendlandianum (sect. Cirrhopetalum) are recorded for the flora of Vietnam for the first time. These species are endemic of the Indochinese Peninsula in a broad sense, except for B. flavescens having wide distribution in western Malesia. Data on ecology, phenology, distribution, brief relevant taxonomic notes, as well as colour photographs and line drawings of the type and voucher specimens are provided for all reported taxa. Lectotypification is provided for B. wendlandianum.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.369.1.1",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.369.1.1",
                    "fulltext": "New species of Bulbophyllum (Orchidaceae) in the flora of Vietnam <p>Three taxa, Bulbophyllum cariniflorum var. orlovii (sect. Pleiophyllum), B. sonii (sect. Anisopetalon) and B. ustulata (sect. Brachystachya) are described as new for science. All of these novelties are local endemics of Vietnam. Additionally, four species, B. flavescens (sect. Aphanobulbon), B. ovatum (sect. Desmosanthes), B. physocoryphum (sect. Macrocaulia) and B. wendlandianum (sect. Cirrhopetalum) are recorded for the flora of Vietnam for the first time. These species are endemic of the Indochinese Peninsula in a broad sense, except for B. flavescens having wide distribution in western Malesia. Data on ecology, phenology, distribution, brief relevant taxonomic notes, as well as colour photographs and line drawings of the type and voucher specimens are provided for all reported taxa. Lectotypification is provided for B. wendlandianum.</p> 10.11646/phytotaxa.369.1.1 NONG VAN DUY LEONID V. AVERYANOV NGUYEN HOANG TUAN MAXIM S. NURALIEV TATIANA V. MAISAK NGUYEN CONG ANH 369 1 1",
                    "fulltext_boosted": "New species of Bulbophyllum (Orchidaceae) in the flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.385.2.5",
            "key": "https://doi.org/10.11646/phytotaxa.385.2.5",
            "value": {
                "id": "https-doi-org-10-11646-phytotaxa-385-2-5",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Loropetalum flavum (Hamamelidaceae), a new species from northern Vietnam",
                    "url": "https://doi.org/10.11646/phytotaxa.385.2.5",
                    "description": "<p>Loropetalum flavum (Hamamelidaceae) is described and illustrated as a new species from Bat Dai Son Mountains situated in the northern Vietnam. Recently discovered plant was observed as a typical element of the rich primary forest found on the highly eroded karstic limestone mountain formations allied to the border with China. The new species is characterized by arboreous habit; stellately indumentum of branchlets, leaves and flowers; axillary, capitate, 4–12-flowered inflorescences; yellow, sessile, actinomorphic, bisexual, 4–6-merous flowers with 2-whorled perianth and 2–8 fleshy disc lobes; stamens with conspicuous subulate connective protrusion; anthers with 2 rectangular 2-sporangiate thecae, each dehiscing by 2 valves and syncarpous gynoecium with 2-locular inferior ovary bearing 2 very short separate styles. A key to all known species of Loropetalum species is given and lectotype of L. lanceum is proposed.</p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/phytotaxa.385.2.5",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/phytotaxa.385.2.5",
                    "fulltext": "Loropetalum flavum (Hamamelidaceae), a new species from northern Vietnam <p>Loropetalum flavum (Hamamelidaceae) is described and illustrated as a new species from Bat Dai Son Mountains situated in the northern Vietnam. Recently discovered plant was observed as a typical element of the rich primary forest found on the highly eroded karstic limestone mountain formations allied to the border with China. The new species is characterized by arboreous habit; stellately indumentum of branchlets, leaves and flowers; axillary, capitate, 4–12-flowered inflorescences; yellow, sessile, actinomorphic, bisexual, 4–6-merous flowers with 2-whorled perianth and 2–8 fleshy disc lobes; stamens with conspicuous subulate connective protrusion; anthers with 2 rectangular 2-sporangiate thecae, each dehiscing by 2 valves and syncarpous gynoecium with 2-locular inferior ovary bearing 2 very short separate styles. A key to all known species of Loropetalum species is given and lectotype of L. lanceum is proposed.</p> 10.11646/phytotaxa.385.2.5 ANNA L. AVERYANOVA LEONID V. AVERYANOV LE NGOC DIEP PETER K. ENDRESS KHANG SINH NGUYEN TRAN HUY THAI TATIANA V. MAISAK 385 2 94",
                    "fulltext_boosted": "Loropetalum flavum (Hamamelidaceae), a new species from northern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/zootaxa.3721.6.4#creator-1",
            "key": "https://doi.org/10.11646/zootaxa.3721.6.4#creator-1",
            "value": {
                "id": "https-doi-org-10-11646-zootaxa-3721-6-4-creator-1",
                "type": "Person",
                "search_result_data": {
                    "name": "CUONG HUYNH",
                    "url": "https://doi.org/10.11646/zootaxa.3721.6.4#creator-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A",
                    "type": "Person",
                    "fulltext": "CUONG HUYNH",
                    "fulltext_boosted": "CUONG HUYNH"
                }
            }
        },
        {
            "id": "https://doi.org/10.11646/zootaxa.4402.2.3",
            "key": "https://doi.org/10.11646/zootaxa.4402.2.3",
            "value": {
                "id": "https-doi-org-10-11646-zootaxa-4402-2-3",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Two new species of penicillate millipedes (Diplopoda, Polyxenidae) from Phu Quoc Island in southern Vietnam",
                    "url": "https://doi.org/10.11646/zootaxa.4402.2.3",
                    "description": "<p>Two new penicillate millipede species from the genera Unixenus and Monographis (Diplopoda, Polyxenidae) were collected from Phu Quoc Island, Vietnam’s largest island. Unixenus intragramineus sp. n. was found within the stem of a creeping grass growing on an unstable, sandy substrate in the intertidal zone. The defining taxonomic characteristics of the genus Unixenus were apparent in U. intragramineus sp. n., but it is distinctively different from all described species. Monographis phuquocensis sp. n. belongs to a group of Monographis species which have their antennal sensilla arranged in a crescent-shape; but the structure of the labrum and telotarsus observed in this new species differs from other described Monographis. </p>"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.11646/zootaxa.4402.2.3",
                    "type": "ScholarlyArticle",
                    "doi": "10.11646/zootaxa.4402.2.3",
                    "fulltext": "Two new species of penicillate millipedes (Diplopoda, Polyxenidae) from Phu Quoc Island in southern Vietnam <p>Two new penicillate millipede species from the genera Unixenus and Monographis (Diplopoda, Polyxenidae) were collected from Phu Quoc Island, Vietnam’s largest island. Unixenus intragramineus sp. n. was found within the stem of a creeping grass growing on an unstable, sandy substrate in the intertidal zone. The defining taxonomic characteristics of the genus Unixenus were apparent in U. intragramineus sp. n., but it is distinctively different from all described species. Monographis phuquocensis sp. n. belongs to a group of Monographis species which have their antennal sensilla arranged in a crescent-shape; but the structure of the labrum and telotarsus observed in this new species differs from other described Monographis. </p> 10.11646/zootaxa.4402.2.3 CUONG HUYNH ANNEKE A VEENSTRA 4402 2 283",
                    "fulltext_boosted": "Two new species of penicillate millipedes (Diplopoda, Polyxenidae) from Phu Quoc Island in southern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.14258/turczaninowia.16.4.7",
            "key": "https://doi.org/10.14258/turczaninowia.16.4.7",
            "value": {
                "id": "https-doi-org-10-14258-turczaninowia-16-4-7",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "NEW AND RARE ORCHIDS (ORCHIDACEAE) IN THE FLORA OF CAMBODIA AND LAOS",
                    "url": "https://doi.org/10.14258/turczaninowia.16.4.7"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.14258/turczaninowia.16.4.7",
                    "type": "ScholarlyArticle",
                    "doi": "10.14258/turczaninowia.16.4.7",
                    "fulltext": "NEW AND RARE ORCHIDS (ORCHIDACEAE) IN THE FLORA OF CAMBODIA AND LAOS 10.14258/turczaninowia.16.4.7 Leonid Averyanov 16 4 26 46",
                    "fulltext_boosted": "NEW AND RARE ORCHIDS (ORCHIDACEAE) IN THE FLORA OF CAMBODIA AND LAOS"
                }
            }
        },
        {
            "id": "https://doi.org/10.14258/turczaninowia.17.1.2",
            "key": "https://doi.org/10.14258/turczaninowia.17.1.2",
            "value": {
                "id": "https-doi-org-10-14258-turczaninowia-17-1-2",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aerides phongii (Orchidaceae), a new species from Southern Vietnam",
                    "url": "https://doi.org/10.14258/turczaninowia.17.1.2"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.14258/turczaninowia.17.1.2",
                    "type": "ScholarlyArticle",
                    "doi": "10.14258/turczaninowia.17.1.2",
                    "fulltext": "Aerides phongii (Orchidaceae), a new species from Southern Vietnam 10.14258/turczaninowia.17.1.2 L. Averyanov P. Loc C. Canh 17 1 6 9",
                    "fulltext_boosted": "Aerides phongii (Orchidaceae), a new species from Southern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.14258/turczaninowia.18.1.1",
            "key": "https://doi.org/10.14258/turczaninowia.18.1.1",
            "value": {
                "id": "https-doi-org-10-14258-turczaninowia-18-1-1",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Preliminary assessment for conservation of Pinus cernua (Pinaceae) with a brief synopsis of related taxa in eastern Indochina",
                    "url": "https://doi.org/10.14258/turczaninowia.18.1.1"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.14258/turczaninowia.18.1.1",
                    "type": "ScholarlyArticle",
                    "doi": "10.14258/turczaninowia.18.1.1",
                    "fulltext": "Preliminary assessment for conservation of Pinus cernua (Pinaceae) with a brief synopsis of related taxa in eastern Indochina 10.14258/turczaninowia.18.1.1 L. V. Averyanov   Khang Sinh Nguyen Hiep Tien Nguyen D.K. Harder     18 1 5 17",
                    "fulltext_boosted": "Preliminary assessment for conservation of Pinus cernua (Pinaceae) with a brief synopsis of related taxa in eastern Indochina"
                }
            }
        },
        {
            "id": "https://doi.org/10.14258/turczaninowia.19.2.4",
            "key": "https://doi.org/10.14258/turczaninowia.19.2.4",
            "value": {
                "id": "https-doi-org-10-14258-turczaninowia-19-2-4",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "The genus Liparis (Orchidaceae) in Hon Ba nature reserve, Vietnam, Khanh Hoa province",
                    "url": "https://doi.org/10.14258/turczaninowia.19.2.4"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.14258/turczaninowia.19.2.4",
                    "type": "ScholarlyArticle",
                    "doi": "10.14258/turczaninowia.19.2.4",
                    "fulltext": "The genus Liparis (Orchidaceae) in Hon Ba nature reserve, Vietnam, Khanh Hoa province 10.14258/turczaninowia.19.2.4 L. Averyanov T. Vuong T. Tam 19 2 34 49",
                    "fulltext_boosted": "The genus Liparis (Orchidaceae) in Hon Ba nature reserve, Vietnam, Khanh Hoa province"
                }
            }
        },
        {
            "id": "https://doi.org/10.2307/3393114",
            "key": "https://doi.org/10.2307/3393114",
            "value": {
                "id": "https-doi-org-10-2307-3393114",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Diplopanax vietnamensis, a New Species of Nyssaceae from Vietnam: One More Living Representative of the Tertiary Flora of Eurasia",
                    "url": "https://doi.org/10.2307/3393114"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.2307/3393114",
                    "type": "ScholarlyArticle",
                    "doi": "10.2307/3393114",
                    "fulltext": "Diplopanax vietnamensis, a New Species of Nyssaceae from Vietnam: One More Living Representative of the Tertiary Flora of Eurasia 10.2307/3393114 Leonid V. Averyanov Nguyen Tien Hiep 12 4 433",
                    "fulltext_boosted": "Diplopanax vietnamensis, a New Species of Nyssaceae from Vietnam: One More Living Representative of the Tertiary Flora of Eurasia"
                }
            }
        },
        {
            "id": "https://doi.org/10.5252/a2015n1a4",
            "key": "https://doi.org/10.5252/a2015n1a4",
            "value": {
                "id": "https-doi-org-10-5252-a2015n1a4",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New species ofOphiopogonKer Gawl.,PeliosanthesAndrews andTupistraKer Gawl. (Asparagaceae) in the flora of Laos and Vietnam",
                    "url": "https://doi.org/10.5252/a2015n1a4"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.5252/a2015n1a4",
                    "type": "ScholarlyArticle",
                    "doi": "10.5252/a2015n1a4",
                    "fulltext": "New species ofOphiopogonKer Gawl.,PeliosanthesAndrews andTupistraKer Gawl. (Asparagaceae) in the flora of Laos and Vietnam 10.5252/a2015n1a4 Leonid V. Averyanov Noriyuki Tanaka Khang Sinh Nguyen Hiep Tien Nguyen Eugene L. Konstantinov 37 1 25 45",
                    "fulltext_boosted": "New species ofOphiopogonKer Gawl.,PeliosanthesAndrews andTupistraKer Gawl. (Asparagaceae) in the flora of Laos and Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2003.48(2).87",
            "key": "https://doi.org/10.6165/tai.2003.48(2).87",
            "value": {
                "id": "https-doi-org-10-6165-tai-2003-48-2-87",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Orchidaceous Additions to the Floras of China and Taiwan/中國與台灣蘭科植物誌新見聞",
                    "url": "https://doi.org/10.6165/tai.2003.48(2).87",
                    "description": "研究標本館內中國與台灣之野生蘭的標本與文獻，發現一些新的與值得注意的資訊。本文提議三份新的組合學名，即：Anoectochilus baotingensis、Oberonia sinica 及Odontochilus nanlingensis。/Literature and herbarium studies of Chinese and Taiwanese orchids has revealed a variety of new and noteworthy data pertinent to the floras of China and Taiwan. Three new combinations are proposed, viz. Anoectochilus baotingensis, Oberonia sinica and Odontochilus nanlingensis."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2003.48(2).87",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2003.48(2).87",
                    "fulltext": "Orchidaceous Additions to the Floras of China and Taiwan 中國與台灣蘭科植物誌新見聞 研究標本館內中國與台灣之野生蘭的標本與文獻，發現一些新的與值得注意的資訊。本文提議三份新的組合學名，即：Anoectochilus baotingensis、Oberonia sinica 及Odontochilus nanlingensis。 Literature and herbarium studies of Chinese and Taiwanese orchids has revealed a variety of new and noteworthy data pertinent to the floras of China and Taiwan. Three new combinations are proposed, viz. Anoectochilus baotingensis, Oberonia sinica and Odontochilus nanlingensis. 10.6165/tai.2003.48(2).87 Paul Ormerod 48 2 87 93",
                    "fulltext_boosted": "Orchidaceous Additions to the Floras of China and Taiwan 中國與台灣蘭科植物誌新見聞"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2007.52(1).12",
            "key": "https://doi.org/10.6165/tai.2007.52(1).12",
            "value": {
                "id": "https-doi-org-10-6165-tai-2007-52-1-12",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "The Genus Sciaphila Blume (Triuridaceae) in the Flora of Vietnam/越南霉草屬（霉草科）植物",
                    "url": "https://doi.org/10.6165/tai.2007.52(1).12",
                    "description": "本文首次報導越南霉草屬（霉草科）二新種及一新紀錄種，並提供檢索表、插圖和描述以供鑑定。/Two species of Sciaphila: S. arcuata and S. stellata (Triuridaceae) discovered in Vietnam are described as new taxa for the genus and one discovered species (S. nana) is first recorded for the country. The key for identification and illustrated descriptions are provided for all species of this genus in the flora of Vietnam."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2007.52(1).12",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2007.52(1).12",
                    "fulltext": "The Genus Sciaphila Blume (Triuridaceae) in the Flora of Vietnam 越南霉草屬（霉草科）植物 本文首次報導越南霉草屬（霉草科）二新種及一新紀錄種，並提供檢索表、插圖和描述以供鑑定。 Two species of Sciaphila: S. arcuata and S. stellata (Triuridaceae) discovered in Vietnam are described as new taxa for the genus and one discovered species (S. nana) is first recorded for the country. The key for identification and illustrated descriptions are provided for all species of this genus in the flora of Vietnam. 10.6165/tai.2007.52(1).12 Leonid V. Averyanov 52 1 12 19",
                    "fulltext_boosted": "The Genus Sciaphila Blume (Triuridaceae) in the Flora of Vietnam 越南霉草屬（霉草科）植物"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2007.52(4).287",
            "key": "https://doi.org/10.6165/tai.2007.52(4).287",
            "value": {
                "id": "https-doi-org-10-6165-tai-2007-52-4-287",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Orchids from Vietnam/越南蘭科新種",
                    "url": "https://doi.org/10.6165/tai.2007.52(4).287",
                    "description": "2005-2007年在越南野外採集的標本經鑑定後發現10種蘭科新種，分別命名為Anoectochilus papillosus, Arundina caespitosa, Bulbophyllum paraemarginatum, B. sinhoënse, Cheirostylis foliosa, Goodyera rhombodoides, Liparis rivularis, Oberonia multidentata, O. trichophora and Sunipia nigricans，並提供繪圖及描述。/Identification of herbarium specimens collected in course of field exploration works in Vietnam during 2005-2007 revealed ten species of orchids new for science. Illustrated descriptions are rovided for each discovered species, which are named as Anoectochilus papillosus, Arundina aespitosa, Bulbophyllum paraemarginatum, B. sinhoënse, Cheirostylis foliosa, Goodyera hombodoides, Liparis rivularis, Oberonia multidentata, O. trichophora and Sunipia nigricans."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2007.52(4).287",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2007.52(4).287",
                    "fulltext": "New Species of Orchids from Vietnam 越南蘭科新種 2005-2007年在越南野外採集的標本經鑑定後發現10種蘭科新種，分別命名為Anoectochilus papillosus, Arundina caespitosa, Bulbophyllum paraemarginatum, B. sinhoënse, Cheirostylis foliosa, Goodyera rhombodoides, Liparis rivularis, Oberonia multidentata, O. trichophora and Sunipia nigricans，並提供繪圖及描述。 Identification of herbarium specimens collected in course of field exploration works in Vietnam during 2005-2007 revealed ten species of orchids new for science. Illustrated descriptions are rovided for each discovered species, which are named as Anoectochilus papillosus, Arundina aespitosa, Bulbophyllum paraemarginatum, B. sinhoënse, Cheirostylis foliosa, Goodyera hombodoides, Liparis rivularis, Oberonia multidentata, O. trichophora and Sunipia nigricans. 10.6165/tai.2007.52(4).287 Leonid V. Averyanov 52 4 287 306",
                    "fulltext_boosted": "New Species of Orchids from Vietnam 越南蘭科新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2009.54(4).311",
            "key": "https://doi.org/10.6165/tai.2009.54(4).311",
            "value": {
                "id": "https-doi-org-10-6165-tai-2009-54-4-311",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Hayata glandulifera (Orchidaceae), New Genus and Species from Northern Vietnam/來自北越的新屬與新種－裂唇早田蘭（蘭科）",
                    "url": "https://doi.org/10.6165/tai.2009.54(4).311",
                    "description": "New orchid related to Cheirostylis, Goodyera, Rhomboda and Zeuxine discovered in lowland central part of northern Vietnam is described in rank of separate genus Hayata. Proposed genus differs from Goodyera in 2 separate lateral stigmas; in not hairy hypochile; in massive, knob-like mesochile and in large 2-lobed, dentate epichile. It differs from Cheirostylis in large flowers with completely free sepals (newer forming tube); in peculiar bunches of capitate glands on lateral walls of hypochile and in not swollen succulent rhizome forming normal adventitious roots, not modified into ridges or pillows covered by root hairs. From Rhomboda discovered genus differs in absence of any keels on the lip; in specific papillae bunches inside hypochile and in not winged column. New genus may be also close to Zeuxine, from which it differs in plant habit, large flowers, large dentate lobes of epichile and in specific shape of stelidia and rostellar arms. Described plant not fits well with any genera of subtribe Goodyerinae and certainly desires generic segregation. Besides Vietnamese plant, described genus includes H. tabiyahanensis from Taiwan and H. sherriffii from Bhutan. Standard taxonomical treatment of new genus and key for its species identification is presented in the paper./來自北越中部低山區的新屬早田蘭與己知的指柱蘭、斑葉蘭、Rhomboda與線柱蘭有密切關係。早田蘭與斑葉蘭屬不同在它具有二個分離的側面柱頭，唇瓣基部內無毛，具有大塊結狀的唇瓣中段與先段形成二片具有裂齒。它與指柱蘭之不同在有甚大的花朵而且花瓣分離，唇瓣基部內側壁上具有腺体，地下莖不具有膨大的節間，與地下莖並沒有被覆根毛。與Rhomboda 屬之不同在新屬唇瓣上不具任何龍骨，唇瓣基部具有內側腺体，蕊柱不具有翅狀附屬物。新屬與線柱蘭屬非常相近，但新屬之外形，大的花朵，裂唇與蕊柱先端之附屬物均可與之區別。除了越南的產地外，新屬尚有分佈於台灣之裂唇早田蘭與分佈於布丹的H. sherriffii。本文內尚介紹了新屬之分類處理與檢索表。"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2009.54(4).311",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2009.54(4).311",
                    "fulltext": "Hayata glandulifera (Orchidaceae), New Genus and Species from Northern Vietnam 來自北越的新屬與新種－裂唇早田蘭（蘭科） New orchid related to Cheirostylis, Goodyera, Rhomboda and Zeuxine discovered in lowland central part of northern Vietnam is described in rank of separate genus Hayata. Proposed genus differs from Goodyera in 2 separate lateral stigmas; in not hairy hypochile; in massive, knob-like mesochile and in large 2-lobed, dentate epichile. It differs from Cheirostylis in large flowers with completely free sepals (newer forming tube); in peculiar bunches of capitate glands on lateral walls of hypochile and in not swollen succulent rhizome forming normal adventitious roots, not modified into ridges or pillows covered by root hairs. From Rhomboda discovered genus differs in absence of any keels on the lip; in specific papillae bunches inside hypochile and in not winged column. New genus may be also close to Zeuxine, from which it differs in plant habit, large flowers, large dentate lobes of epichile and in specific shape of stelidia and rostellar arms. Described plant not fits well with any genera of subtribe Goodyerinae and certainly desires generic segregation. Besides Vietnamese plant, described genus includes H. tabiyahanensis from Taiwan and H. sherriffii from Bhutan. Standard taxonomical treatment of new genus and key for its species identification is presented in the paper. 來自北越中部低山區的新屬早田蘭與己知的指柱蘭、斑葉蘭、Rhomboda與線柱蘭有密切關係。早田蘭與斑葉蘭屬不同在它具有二個分離的側面柱頭，唇瓣基部內無毛，具有大塊結狀的唇瓣中段與先段形成二片具有裂齒。它與指柱蘭之不同在有甚大的花朵而且花瓣分離，唇瓣基部內側壁上具有腺体，地下莖不具有膨大的節間，與地下莖並沒有被覆根毛。與Rhomboda 屬之不同在新屬唇瓣上不具任何龍骨，唇瓣基部具有內側腺体，蕊柱不具有翅狀附屬物。新屬與線柱蘭屬非常相近，但新屬之外形，大的花朵，裂唇與蕊柱先端之附屬物均可與之區別。除了越南的產地外，新屬尚有分佈於台灣之裂唇早田蘭與分佈於布丹的H. sherriffii。本文內尚介紹了新屬之分類處理與檢索表。 10.6165/tai.2009.54(4).311 Leonid V. Averyanov 54 4 311 316",
                    "fulltext_boosted": "Hayata glandulifera (Orchidaceae), New Genus and Species from Northern Vietnam 來自北越的新屬與新種－裂唇早田蘭（蘭科）"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2010.55(2).91",
            "key": "https://doi.org/10.6165/tai.2010.55(2).91",
            "value": {
                "id": "https-doi-org-10-6165-tai-2010-55-2-91",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Three New Species of Orchids (Orchidaceae) from Vietnam/越南蘭科植物三新種",
                    "url": "https://doi.org/10.6165/tai.2010.55(2).91",
                    "description": "本文介紹越南三種蘭科新植物，他們分別是Cheirostylis cristata (與C. bipunctata，C. chinensis 相似)，Didymoplexiella denticulata （與D. ornata，D. siamensis 相似）與Habenaria luceana （與H. geniculata, H. ecalcarata, H. malintana and H. parageniculata諸種類似）。此外並提供每一物種之詳細描述、手畫圖、開花時間、生態與分佈。/Three new orchid species are described from Vietnam as a new for science. They are-Cheirostylis cristata (related to C. bipunctata and C. chinensis), Didymoplexiella denticulata (related to D. ornata and D. siamensis) and Habenaria luceana (similar superficially to H. geniculata, H. ecalcarata, H. malintana and H. parageniculata, but having rather isolated taxonomic position). Detailed description, illustrations, data on flowering time, ecology and distribution are provided for each recognized species."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2010.55(2).91",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2010.55(2).91",
                    "fulltext": "Three New Species of Orchids (Orchidaceae) from Vietnam 越南蘭科植物三新種 本文介紹越南三種蘭科新植物，他們分別是Cheirostylis cristata (與C. bipunctata，C. chinensis 相似)，Didymoplexiella denticulata （與D. ornata，D. siamensis 相似）與Habenaria luceana （與H. geniculata, H. ecalcarata, H. malintana and H. parageniculata諸種類似）。此外並提供每一物種之詳細描述、手畫圖、開花時間、生態與分佈。 Three new orchid species are described from Vietnam as a new for science. They are-Cheirostylis cristata (related to C. bipunctata and C. chinensis), Didymoplexiella denticulata (related to D. ornata and D. siamensis) and Habenaria luceana (similar superficially to H. geniculata, H. ecalcarata, H. malintana and H. parageniculata, but having rather isolated taxonomic position). Detailed description, illustrations, data on flowering time, ecology and distribution are provided for each recognized species. 10.6165/tai.2010.55(2).91 Leonid V. Averyanov 55 2 91 98",
                    "fulltext_boosted": "Three New Species of Orchids (Orchidaceae) from Vietnam 越南蘭科植物三新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2011.56(1).50",
            "key": "https://doi.org/10.6165/tai.2011.56(1).50",
            "value": {
                "id": "https-doi-org-10-6165-tai-2011-56-1-50",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Nervilia gracilis-A New Orchid Species from Northern Vietnam/Nervilia gracilis-越南北部的新種蘭科植物",
                    "url": "https://doi.org/10.6165/tai.2011.56(1).50",
                    "description": "本文敘述並描繪越南北部發現的新物種Nervilia gracilis。該種屬於小型單花的種群，且與寮國的N.calcicola相近；但由唇盤具龍骨狀突起，以及很小的膜質葉片可與之區辨。/Nervilia gracilis-a new species for science discovered in northern Vietnam was described and illustrated. This species belongs to complex of miniature 1-flowered species and has the closest relation to Laotian N. calcicola, from which it differs in keeled disk of the lip and very small membranaceous leaves."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2011.56(1).50",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2011.56(1).50",
                    "fulltext": "Nervilia gracilis-A New Orchid Species from Northern Vietnam Nervilia gracilis-越南北部的新種蘭科植物 本文敘述並描繪越南北部發現的新物種Nervilia gracilis。該種屬於小型單花的種群，且與寮國的N.calcicola相近；但由唇盤具龍骨狀突起，以及很小的膜質葉片可與之區辨。 Nervilia gracilis-a new species for science discovered in northern Vietnam was described and illustrated. This species belongs to complex of miniature 1-flowered species and has the closest relation to Laotian N. calcicola, from which it differs in keeled disk of the lip and very small membranaceous leaves. 10.6165/tai.2011.56(1).50 Leonid V. Averyanov 56 1 50 53",
                    "fulltext_boosted": "Nervilia gracilis-A New Orchid Species from Northern Vietnam Nervilia gracilis-越南北部的新種蘭科植物"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2011.56(2).143",
            "key": "https://doi.org/10.6165/tai.2011.56(2).143",
            "value": {
                "id": "https-doi-org-10-6165-tai-2011-56-2-143",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Peliosanthes yunnanensis and Trichosma yanshanensis-New Additions to the Flora of Vietnam/越南植物誌之新見－雲南球子草(Peliosanthes yunnanensis)及硯山毛蘭(Trichosma yanshanensis)",
                    "url": "https://doi.org/10.6165/tai.2011.56(2).143",
                    "description": "兩種少有報導的稀有植物－雲南球子草(Peliosanthes yunnanensis)和硯山毛蘭(Trichosma yanshanensis)最近在越南被發現。本文除了報導此二種植物之分類、形態特徵、模式標本、生境、分布及繪圖外，亦特別說明此二物種：Trichosmachlorantha (Aver. & Averyanova) Aver. 及硯山毛蘭(T. yanshanensis (S.-C. Chen)Aver.)之新組合的命名。/Two rare, poorly known species-Peliosanthes yunnanensis (Convallariaceae) and Trichosma yanshanensis (Orchidaceae) were recently discovered in Vietnam representing new additions to the flora of the country. Detailed information on taxonomy, types, morphology, ecology, distribution and studied voucher specimens, as well as illustrations are provided here for both species. Two necessary nomenclature combinations are also proposed, namely-Trichosma chlorantha (Aver. & Averyanova) Aver. and T. yanshanensis (S.-C. Chen) Aver."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2011.56(2).143",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2011.56(2).143",
                    "fulltext": "Peliosanthes yunnanensis and Trichosma yanshanensis-New Additions to the Flora of Vietnam 越南植物誌之新見－雲南球子草(Peliosanthes yunnanensis)及硯山毛蘭(Trichosma yanshanensis) 兩種少有報導的稀有植物－雲南球子草(Peliosanthes yunnanensis)和硯山毛蘭(Trichosma yanshanensis)最近在越南被發現。本文除了報導此二種植物之分類、形態特徵、模式標本、生境、分布及繪圖外，亦特別說明此二物種：Trichosmachlorantha (Aver. & Averyanova) Aver. 及硯山毛蘭(T. yanshanensis (S.-C. Chen)Aver.)之新組合的命名。 Two rare, poorly known species-Peliosanthes yunnanensis (Convallariaceae) and Trichosma yanshanensis (Orchidaceae) were recently discovered in Vietnam representing new additions to the flora of the country. Detailed information on taxonomy, types, morphology, ecology, distribution and studied voucher specimens, as well as illustrations are provided here for both species. Two necessary nomenclature combinations are also proposed, namely-Trichosma chlorantha (Aver. & Averyanova) Aver. and T. yanshanensis (S.-C. Chen) Aver. 10.6165/tai.2011.56(2).143 Leonid V. Averyanov 56 2 143 148",
                    "fulltext_boosted": "Peliosanthes yunnanensis and Trichosma yanshanensis-New Additions to the Flora of Vietnam 越南植物誌之新見－雲南球子草(Peliosanthes yunnanensis)及硯山毛蘭(Trichosma yanshanensis)"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2012.57.372",
            "key": "https://doi.org/10.6165/tai.2012.57.372",
            "value": {
                "id": "https-doi-org-10-6165-tai-2012-57-372",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Hymenorchis phitamii (Orchidaceae)-New Genus and Species in the Flora of Vietnam/來自越南的新種兼Hymenorchis屬新紀錄分布—Hymenorchis phitamii",
                    "url": "https://doi.org/10.6165/tai.2012.57.372",
                    "description": "本文發表了在越南發現的蘭科新種Hymenorchis phitamii。與近似物種H. javanica相較，本種的唇瓣尖端凹陷、花被片和葉片邊緣呈細鋸齒狀，這也是首次在亞洲大陸發現Hymenorchis屬的分布，也為越南植物誌、中印半島及亞洲大陸增添一筆引人注目的發現。/Hymenorchis phitamii - new species for science discovered in southern Vietnam described and illustrated. From most closely related H. javanica it differs in emarginate orbicular lip and nearly straight (or hardly serrulate) tepals and leaves. The first record of the genus Hymenorchis in mainland Asia represents new remarkable addition to the orchid flora of Vietnam, as well as floras of Indochina and mainland Asia."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2012.57.372",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2012.57.372",
                    "fulltext": "Hymenorchis phitamii (Orchidaceae)-New Genus and Species in the Flora of Vietnam 來自越南的新種兼Hymenorchis屬新紀錄分布—Hymenorchis phitamii 本文發表了在越南發現的蘭科新種Hymenorchis phitamii。與近似物種H. javanica相較，本種的唇瓣尖端凹陷、花被片和葉片邊緣呈細鋸齒狀，這也是首次在亞洲大陸發現Hymenorchis屬的分布，也為越南植物誌、中印半島及亞洲大陸增添一筆引人注目的發現。 Hymenorchis phitamii - new species for science discovered in southern Vietnam described and illustrated. From most closely related H. javanica it differs in emarginate orbicular lip and nearly straight (or hardly serrulate) tepals and leaves. The first record of the genus Hymenorchis in mainland Asia represents new remarkable addition to the orchid flora of Vietnam, as well as floras of Indochina and mainland Asia. 10.6165/tai.2012.57.372 Leonid V. Averyanov Nong Van Duy Phan Ke Loc 57 4 372 376",
                    "fulltext_boosted": "Hymenorchis phitamii (Orchidaceae)-New Genus and Species in the Flora of Vietnam 來自越南的新種兼Hymenorchis屬新紀錄分布—Hymenorchis phitamii"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2012.57(1).49",
            "key": "https://doi.org/10.6165/tai.2012.57(1).49",
            "value": {
                "id": "https-doi-org-10-6165-tai-2012-57-1-49",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species from Vietnam-Hoya lockii (Apocynaceae, Asclepiadoideae)/越南的新種－Hoya lockii（夾竹桃科，蘿藦亞科Apocynaceae, Asclepiadoideae）",
                    "url": "https://doi.org/10.6165/tai.2012.57(1).49",
                    "description": "本文描述並說明在越南中部的順化省（Thua Thien-Hue Province）所發現的新種Hoya lockii（夾竹桃科，蘿藦亞科）。H. lockii 和與之關係密切的物種H. multiflora Blume進行了形態特徵的比較。Hoya lockii不同於H. multiflora在於除了葉身及副花冠，其他所有的部位均被有柔毛；以及具有白色至乳白色的花冠裂片和先端突尖的副花冠。/The new species Hoya lockii (Apocynaceae, Asclepiadoideae) was discovered in the Thua Thien-Hue Province of central Vietnam and is here described and illustrated. The morphological characters of H. lockii and its closely related species H. multiflora Blume are compared. Hoya lockii differs from H. multiflora in being pubescent in all parts of the plant except for the leaf blade and the corona, as well as in having white-opalescent corolla lobes and mucronate apex of corona lobes."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2012.57(1).49",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2012.57(1).49",
                    "fulltext": "New Species from Vietnam-Hoya lockii (Apocynaceae, Asclepiadoideae) 越南的新種－Hoya lockii（夾竹桃科，蘿藦亞科Apocynaceae, Asclepiadoideae） 本文描述並說明在越南中部的順化省（Thua Thien-Hue Province）所發現的新種Hoya lockii（夾竹桃科，蘿藦亞科）。H. lockii 和與之關係密切的物種H. multiflora Blume進行了形態特徵的比較。Hoya lockii不同於H. multiflora在於除了葉身及副花冠，其他所有的部位均被有柔毛；以及具有白色至乳白色的花冠裂片和先端突尖的副花冠。 The new species Hoya lockii (Apocynaceae, Asclepiadoideae) was discovered in the Thua Thien-Hue Province of central Vietnam and is here described and illustrated. The morphological characters of H. lockii and its closely related species H. multiflora Blume are compared. Hoya lockii differs from H. multiflora in being pubescent in all parts of the plant except for the leaf blade and the corona, as well as in having white-opalescent corolla lobes and mucronate apex of corona lobes. 10.6165/tai.2012.57(1).49 The Pham Van Leonid V. Averyanov 57 1 49 54",
                    "fulltext_boosted": "New Species from Vietnam-Hoya lockii (Apocynaceae, Asclepiadoideae) 越南的新種－Hoya lockii（夾竹桃科，蘿藦亞科Apocynaceae, Asclepiadoideae）"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2012.57(2).127",
            "key": "https://doi.org/10.6165/tai.2012.57(2).127",
            "value": {
                "id": "https-doi-org-10-6165-tai-2012-57-2-127",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Orchid Taxa and Records in the Flora of Vietnam/越南植物誌蘭科之新分類群與新紀錄種",
                    "url": "https://doi.org/10.6165/tai.2012.57(2).127",
                    "description": "Paper presents descriptions of 1 new genus (Theana), 4 new species (Theana vietnamica, Bulbophyllum salmoneum, Sarcoglyphis brevilabia, Schoenorchis scolopendria), 1 new variety (Dendrobium thyrsiflorum var. minutiflorum) and provides data on 21 species of orchids (Bulbophyllum bicolor, B. muscicola, B. nigrescens, B. putii, B. violaceolabellum, Calanthe mannii, C. whiteana, Coelogyne micrantha, Cymbidium cyperifolium, Dendrobium dixanthum, D. findlayanum, D. metrium, D. senile, Didymoplexiella ornata, Luisia thailandica, Monomeria gymnopus, Papilionanthe teres, Schoenorchis fragrans, Stereochilus brevirachis, S. erinaceus, Vanda brunnea) newly recorded in the flora of Vietnam./本篇研究針對越南的蘭科植物描述了一個新屬（Theana），四個新種（Theana vietnamica, Bulbophyllum salmoneum, Sarcoglyphis brevilabia, Schoenorchis scolopendria），一個新變種（Dendrobium thyrsiflorum var. minutiflorum）並提供了越南植物誌新增的二十一個蘭科新紀錄種（Bulbophyllum bicolor, B. muscicola, B. nigrescens, B. putii, B. violaceolabellum, Calanthe mannii, C. whiteana, Coelogyne micrantha, Cymbidium cyperifolium, Dendrobium dixanthum, D. findlayanum, D. metrium, D. senile, Didymoplexiella ornata, Luisia thailandica, Monomeria gymnopus, Papilionanthe teres, Schoenorchis fragrans, Stereochilus brevirachis, S. erinaceus, Vanda brunnea）之相關資料。"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2012.57(2).127",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2012.57(2).127",
                    "fulltext": "New Orchid Taxa and Records in the Flora of Vietnam 越南植物誌蘭科之新分類群與新紀錄種 Paper presents descriptions of 1 new genus (Theana), 4 new species (Theana vietnamica, Bulbophyllum salmoneum, Sarcoglyphis brevilabia, Schoenorchis scolopendria), 1 new variety (Dendrobium thyrsiflorum var. minutiflorum) and provides data on 21 species of orchids (Bulbophyllum bicolor, B. muscicola, B. nigrescens, B. putii, B. violaceolabellum, Calanthe mannii, C. whiteana, Coelogyne micrantha, Cymbidium cyperifolium, Dendrobium dixanthum, D. findlayanum, D. metrium, D. senile, Didymoplexiella ornata, Luisia thailandica, Monomeria gymnopus, Papilionanthe teres, Schoenorchis fragrans, Stereochilus brevirachis, S. erinaceus, Vanda brunnea) newly recorded in the flora of Vietnam. 本篇研究針對越南的蘭科植物描述了一個新屬（Theana），四個新種（Theana vietnamica, Bulbophyllum salmoneum, Sarcoglyphis brevilabia, Schoenorchis scolopendria），一個新變種（Dendrobium thyrsiflorum var. minutiflorum）並提供了越南植物誌新增的二十一個蘭科新紀錄種（Bulbophyllum bicolor, B. muscicola, B. nigrescens, B. putii, B. violaceolabellum, Calanthe mannii, C. whiteana, Coelogyne micrantha, Cymbidium cyperifolium, Dendrobium dixanthum, D. findlayanum, D. metrium, D. senile, Didymoplexiella ornata, Luisia thailandica, Monomeria gymnopus, Papilionanthe teres, Schoenorchis fragrans, Stereochilus brevirachis, S. erinaceus, Vanda brunnea）之相關資料。 10.6165/tai.2012.57(2).127 Leonid V. Averyanov 57 2 127 152",
                    "fulltext_boosted": "New Orchid Taxa and Records in the Flora of Vietnam 越南植物誌蘭科之新分類群與新紀錄種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2012.57(2).153",
            "key": "https://doi.org/10.6165/tai.2012.57(2).153",
            "value": {
                "id": "https-doi-org-10-6165-tai-2012-57-2-153",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Peliosanthes and Tupistra (Asparagaceae) from Eastern Indochina/中南半島東部發現球子草屬（Peliosanthes）與開口箭屬（Tupistra）（天門冬科）新種",
                    "url": "https://doi.org/10.6165/tai.2012.57(2).153",
                    "description": "本文報導近來中南半島東部（越南和寮國）大規模野外調查發現的五種球子草屬新種（P. argenteostriata, P. grandiflora, P. nivea, P. nutans, P. retroflexa）與一天門冬科開口箭屬新種（T. theana），並佐以手繪圖描述。上述新種皆為特有種且生長地範圍狹窄。每一新種的敘述資料包含標準的引用模式標本、描述、副模標本（paratype）列表、建議種名詞源、生態學及分佈數據和簡要分類備註。/Five new species of Peliosanthes (P. argenteostriata, P. grandiflora, P. nivea, P. nutans, P. retroflexa) and one species of Tupistra (T. theana) of Asparagaceae (Convallariaceae s.str.) family discovered recently in eastern Indochina (Vietnam and Laos) during extensive field work are described and illustrated. All described species are local endemics with very restricted geographical range. Data for each described species comprise standard citation of type specimens, description, list of paratypes, proposed species epithet etymology, data on ecology and distribution, as well as short taxonomic remarks."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2012.57(2).153",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2012.57(2).153",
                    "fulltext": "New Species of Peliosanthes and Tupistra (Asparagaceae) from Eastern Indochina 中南半島東部發現球子草屬（Peliosanthes）與開口箭屬（Tupistra）（天門冬科）新種 本文報導近來中南半島東部（越南和寮國）大規模野外調查發現的五種球子草屬新種（P. argenteostriata, P. grandiflora, P. nivea, P. nutans, P. retroflexa）與一天門冬科開口箭屬新種（T. theana），並佐以手繪圖描述。上述新種皆為特有種且生長地範圍狹窄。每一新種的敘述資料包含標準的引用模式標本、描述、副模標本（paratype）列表、建議種名詞源、生態學及分佈數據和簡要分類備註。 Five new species of Peliosanthes (P. argenteostriata, P. grandiflora, P. nivea, P. nutans, P. retroflexa) and one species of Tupistra (T. theana) of Asparagaceae (Convallariaceae s.str.) family discovered recently in eastern Indochina (Vietnam and Laos) during extensive field work are described and illustrated. All described species are local endemics with very restricted geographical range. Data for each described species comprise standard citation of type specimens, description, list of paratypes, proposed species epithet etymology, data on ecology and distribution, as well as short taxonomic remarks. 10.6165/tai.2012.57(2).153 Leonid V. Averyanov Noriyuki Tanaka 57 2 153 167",
                    "fulltext_boosted": "New Species of Peliosanthes and Tupistra (Asparagaceae) from Eastern Indochina 中南半島東部發現球子草屬（Peliosanthes）與開口箭屬（Tupistra）（天門冬科）新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2013.58.108",
            "key": "https://doi.org/10.6165/tai.2013.58.108",
            "value": {
                "id": "https-doi-org-10-6165-tai-2013-58-108",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Aspidistra truongii-A New Species of Asparagaceae (Convallariaceae s.str.) from Southern Vietnam/越南南部發現的天門冬科新種－Aspidistra truongii",
                    "url": "https://doi.org/10.6165/tai.2013.58.108",
                    "description": "Aspidistra truongii, discovered in southern Vietnam is described and illustrated as new for science. Large nutant flowers are a unique feature of the new species distinguishing it from all known congeners./本文發表一種在越南南方發現的新種Aspidistra truongii，並提供照片與描述。本種獨特的特徵為下垂的大型花朵，並可藉此特徵與同屬的其他植物區分。"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2013.58.108",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2013.58.108",
                    "fulltext": "Aspidistra truongii-A New Species of Asparagaceae (Convallariaceae s.str.) from Southern Vietnam 越南南部發現的天門冬科新種－Aspidistra truongii Aspidistra truongii, discovered in southern Vietnam is described and illustrated as new for science. Large nutant flowers are a unique feature of the new species distinguishing it from all known congeners. 本文發表一種在越南南方發現的新種Aspidistra truongii，並提供照片與描述。本種獨特的特徵為下垂的大型花朵，並可藉此特徵與同屬的其他植物區分。 10.6165/tai.2013.58.108 Leonid V. Averyanov Hans-Juergen Tillich 58 2 108 111",
                    "fulltext_boosted": "Aspidistra truongii-A New Species of Asparagaceae (Convallariaceae s.str.) from Southern Vietnam 越南南部發現的天門冬科新種－Aspidistra truongii"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2013.58.251",
            "key": "https://doi.org/10.6165/tai.2013.58.251",
            "value": {
                "id": "https-doi-org-10-6165-tai-2013-58-251",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Vietorchis Furcata (Orchidaceae, Vietorchidinae)-A New Species from Southern Vietnam/越南南部發現的蘭科新種－Vietorchis furcata",
                    "url": "https://doi.org/10.6165/tai.2013.58.251",
                    "description": "本文發表一種在越南南部發現的非光合作用蘭科植物Vietorchis furcata，並提供描述與手繪圖。本種的發現也為原本認為是單種屬的Vietorchis屬增添一員，本文也提供了檢索表與分類概要以供辨別此屬內的兩物種。Vietorchis屬與Silvorchis屬因為彼此特有的分類地位而歸類於Vietorchidinae亞族中。/Vietorchis furcata, an achlorophyllous orchid, discovered in southern Vietnam is described and illustrated as new for science. This is the second species of the genus regarded earlier as monotypic. A key for identification of both species of the genus and short notes on their taxonomy and biology are provided. Closely related genera-Vietorchis and Silvorchis are segregated in rank of subtribe Vietorchidinae due to their isolated taxonomical position."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2013.58.251",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2013.58.251",
                    "fulltext": "Vietorchis Furcata (Orchidaceae, Vietorchidinae)-A New Species from Southern Vietnam 越南南部發現的蘭科新種－Vietorchis furcata 本文發表一種在越南南部發現的非光合作用蘭科植物Vietorchis furcata，並提供描述與手繪圖。本種的發現也為原本認為是單種屬的Vietorchis屬增添一員，本文也提供了檢索表與分類概要以供辨別此屬內的兩物種。Vietorchis屬與Silvorchis屬因為彼此特有的分類地位而歸類於Vietorchidinae亞族中。 Vietorchis furcata, an achlorophyllous orchid, discovered in southern Vietnam is described and illustrated as new for science. This is the second species of the genus regarded earlier as monotypic. A key for identification of both species of the genus and short notes on their taxonomy and biology are provided. Closely related genera-Vietorchis and Silvorchis are segregated in rank of subtribe Vietorchidinae due to their isolated taxonomical position. 10.6165/tai.2013.58.251 Svetlana P. Kuznetsova Maxim S. Nuraliev Leonid V. Averyanov Andrey N. Kuznetsov 58 4 251 256",
                    "fulltext_boosted": "Vietorchis Furcata (Orchidaceae, Vietorchidinae)-A New Species from Southern Vietnam 越南南部發現的蘭科新種－Vietorchis furcata"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2013.58.4.233",
            "key": "https://doi.org/10.6165/tai.2013.58.4.233",
            "value": {
                "id": "https-doi-org-10-6165-tai-2013-58-4-233",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Cambodia and Vietnam/自越南與柬埔寨發現的沿階草屬與球子草屬（天門冬科）新種",
                    "url": "https://doi.org/10.6165/tai.2013.58.4.233",
                    "description": "本文發表三個新物種，分別自越南與柬埔寨南部發現的兩種沿階草（O. rupestris，O. tristylatus），及一種球子草（P. cambodiana）；本文除提供物種的分類描述與圖片外，也提供每個物種所引用的模式標本資訊、副模標本清單、種小名之來源和生態資訊以供辨別。這三個物種很有可能都是特有種且分布範圍狹窄。/Two new species of Ophiopogon (O. rupestris, O. tristylatus) and one species of Peliosanthes (P. cambodiana) of Asparagaceae (Convallariaceae s. str.) discovered recently in southern Cambodia and in Vietnam are described and illustrated. All described species are probably local endemics with a restricted distribution. Data reported for each described species comprise a standard citation of type specimens, description, list of available paratypes, proposed species epithet etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2013.58.4.233",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2013.58.4.233",
                    "fulltext": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Cambodia and Vietnam 自越南與柬埔寨發現的沿階草屬與球子草屬（天門冬科）新種 本文發表三個新物種，分別自越南與柬埔寨南部發現的兩種沿階草（O. rupestris，O. tristylatus），及一種球子草（P. cambodiana）；本文除提供物種的分類描述與圖片外，也提供每個物種所引用的模式標本資訊、副模標本清單、種小名之來源和生態資訊以供辨別。這三個物種很有可能都是特有種且分布範圍狹窄。 Two new species of Ophiopogon (O. rupestris, O. tristylatus) and one species of Peliosanthes (P. cambodiana) of Asparagaceae (Convallariaceae s. str.) discovered recently in southern Cambodia and in Vietnam are described and illustrated. All described species are probably local endemics with a restricted distribution. Data reported for each described species comprise a standard citation of type specimens, description, list of available paratypes, proposed species epithet etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks. 10.6165/tai.2013.58.4.233 Leonid V. Averyanov Noriyuki Tanaka Hong Truong Luu 58 4 233 241",
                    "fulltext_boosted": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Cambodia and Vietnam 自越南與柬埔寨發現的沿階草屬與球子草屬（天門冬科）新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2014.59.1",
            "key": "https://doi.org/10.6165/tai.2014.59.1",
            "value": {
                "id": "https-doi-org-10-6165-tai-2014-59-1",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "The Genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam/越南的蜘蛛抱蛋屬（天門冬科）",
                    "url": "https://doi.org/10.6165/tai.2014.59.1",
                    "description": "回顧越南蜘蛛抱蛋屬的歷史，顯示過去十年來此屬在物種數上的大量成長。本文針對目前越南蜘蛛抱蛋屬下43個現存物種，設計了較以往更廣泛、更全面的檢索表，以期對將來的物種鑑定、田野調查能有所助益，也希望未來能刺激更多對這多樣化的屬之相關研究。/A historical outline of the knowledge of Aspidistra in Vietnam highlights the enormous increase of well-known species number during the past decade. An extended and comprehensive determination key for the currently known 43 Vietnamese Aspidistra species is designed to summarize the actual state of knowledge, to facilitate determination, to stimulate further field exploration and biological studies of this extraordinarily diverse genus."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2014.59.1",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2014.59.1",
                    "fulltext": "The Genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam 越南的蜘蛛抱蛋屬（天門冬科） 回顧越南蜘蛛抱蛋屬的歷史，顯示過去十年來此屬在物種數上的大量成長。本文針對目前越南蜘蛛抱蛋屬下43個現存物種，設計了較以往更廣泛、更全面的檢索表，以期對將來的物種鑑定、田野調查能有所助益，也希望未來能刺激更多對這多樣化的屬之相關研究。 A historical outline of the knowledge of Aspidistra in Vietnam highlights the enormous increase of well-known species number during the past decade. An extended and comprehensive determination key for the currently known 43 Vietnamese Aspidistra species is designed to summarize the actual state of knowledge, to facilitate determination, to stimulate further field exploration and biological studies of this extraordinarily diverse genus. 10.6165/tai.2014.59.1 Hans-Juergen Tillich 59 1 1 8",
                    "fulltext_boosted": "The Genus Aspidistra Ker-Gawl. (Asparagaceae) in Vietnam 越南的蜘蛛抱蛋屬（天門冬科）"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2014.59.13",
            "key": "https://doi.org/10.6165/tai.2014.59.13",
            "value": {
                "id": "https-doi-org-10-6165-tai-2014-59-13",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Chionographis (Melanthiaceae) from Eastern Indochina/中南半島西部發現的白絲草屬（黑藥花科）新種",
                    "url": "https://doi.org/10.6165/tai.2014.59.13",
                    "description": "本文新發現一種在越南中部與寮國邊界的白絲草屬新種Chionographis actinomorpha，並提供模式標本引用、分類描述、命名詞源、生態習性、物候學及地理分布等資訊。本種花直立，輻射對稱，且花被片等長等特徵與同屬其它物種具有明顯的差異。本種的發現也大大擴展了白絲草屬在中南半島最南的分布範圍。/New species-Chionographis actinomorpha (Melanthiaceae) discovered on the border of central Vietnam and Laos is described and illustrated. Standard citations of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks for the new species are provided. The species differs from all its congeners in having antrorse, actinomorphic flowers with tepals of the same length. This fact may also imply that this new species retains more primitive character states than any other species of this genus. Discovery of this species in middle-east Indochina remarkably extends the generic range hitherto known to the far southwest."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2014.59.13",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2014.59.13",
                    "fulltext": "New Species of Chionographis (Melanthiaceae) from Eastern Indochina 中南半島西部發現的白絲草屬（黑藥花科）新種 本文新發現一種在越南中部與寮國邊界的白絲草屬新種Chionographis actinomorpha，並提供模式標本引用、分類描述、命名詞源、生態習性、物候學及地理分布等資訊。本種花直立，輻射對稱，且花被片等長等特徵與同屬其它物種具有明顯的差異。本種的發現也大大擴展了白絲草屬在中南半島最南的分布範圍。 New species-Chionographis actinomorpha (Melanthiaceae) discovered on the border of central Vietnam and Laos is described and illustrated. Standard citations of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks for the new species are provided. The species differs from all its congeners in having antrorse, actinomorphic flowers with tepals of the same length. This fact may also imply that this new species retains more primitive character states than any other species of this genus. Discovery of this species in middle-east Indochina remarkably extends the generic range hitherto known to the far southwest. 10.6165/tai.2014.59.13 Leonid V. Averyanov Noriyuki Tanaka 59 1 13 17",
                    "fulltext_boosted": "New Species of Chionographis (Melanthiaceae) from Eastern Indochina 中南半島西部發現的白絲草屬（黑藥花科）新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2014.59.18",
            "key": "https://doi.org/10.6165/tai.2014.59.18",
            "value": {
                "id": "https-doi-org-10-6165-tai-2014-59-18",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Peliosanthes and Rohdea (Asparagaceae) from Eastern Indochina/中南半島西部發現之球子草屬與萬年青屬（天門冬科）新種",
                    "url": "https://doi.org/10.6165/tai.2014.59.18",
                    "description": "本文發現天門冬科兩新種，分別是Peliosanthes triandra和Rohdea dracaenoides，採集地點在柬埔寨南部和寮國中部，兩者都很有可能是分布範圍侷限的特有種。本文提供模式標本、分類描述、命名詞源、生態習性、物候學及地理分布等資訊以供辨認。/Two new species-Peliosanthes triandra and Rohdea dracaenoides of Asparagaceae (Convallariaceae s. str.) discovered recently in southern Cambodia and in central Laos are described and illustrated. Both described species are probably local endemics with a restricted distribution. Data for the species reported comprise a standard citation of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2014.59.18",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2014.59.18",
                    "fulltext": "New Species of Peliosanthes and Rohdea (Asparagaceae) from Eastern Indochina 中南半島西部發現之球子草屬與萬年青屬（天門冬科）新種 本文發現天門冬科兩新種，分別是Peliosanthes triandra和Rohdea dracaenoides，採集地點在柬埔寨南部和寮國中部，兩者都很有可能是分布範圍侷限的特有種。本文提供模式標本、分類描述、命名詞源、生態習性、物候學及地理分布等資訊以供辨認。 Two new species-Peliosanthes triandra and Rohdea dracaenoides of Asparagaceae (Convallariaceae s. str.) discovered recently in southern Cambodia and in central Laos are described and illustrated. Both described species are probably local endemics with a restricted distribution. Data for the species reported comprise a standard citation of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks. 10.6165/tai.2014.59.18 Leonid V. Averyanov Noriyuki Tanaka Sinh Khang Nguyen 59 1 18 25",
                    "fulltext_boosted": "New Species of Peliosanthes and Rohdea (Asparagaceae) from Eastern Indochina 中南半島西部發現之球子草屬與萬年青屬（天門冬科）新種"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2014.59.4.348",
            "key": "https://doi.org/10.6165/tai.2014.59.4.348",
            "value": {
                "id": "https-doi-org-10-6165-tai-2014-59-4-348",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "＂Sinocrassula vietnamenis＂ (Crassulaceae), New Species and New Generic Record in the Flora of Vietnam",
                    "url": "https://doi.org/10.6165/tai.2014.59.4.348",
                    "description": "New species,-＂Sinocrassula vietnamensis (Crassulaceae)＂ discovered in the Northern Vietnam is described and illustrated. Standard citations of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks for the new species are provided. The species differs from its closest ally ＂S. diversifolia＂ in large well developed rosettes, hairy leaves, white flowers and long styles. The discovery of this species in Vietnam remarkably extends the geographical range of the genus southwards."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2014.59.4.348",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2014.59.4.348",
                    "fulltext": "＂Sinocrassula vietnamenis＂ (Crassulaceae), New Species and New Generic Record in the Flora of Vietnam New species,-＂Sinocrassula vietnamensis (Crassulaceae)＂ discovered in the Northern Vietnam is described and illustrated. Standard citations of type specimens, description, name etymology, data on ecology, phenology and distribution, as well as short taxonomic remarks for the new species are provided. The species differs from its closest ally ＂S. diversifolia＂ in large well developed rosettes, hairy leaves, white flowers and long styles. The discovery of this species in Vietnam remarkably extends the geographical range of the genus southwards. 10.6165/tai.2014.59.4.348 Leonid V. Averyanov Vyacheslav V. Byalt The Van Pham Nguyen Tien Vinh Phan Ke Loc Nguyen Quang Hieu 59 4 348 352",
                    "fulltext_boosted": "＂Sinocrassula vietnamenis＂ (Crassulaceae), New Species and New Generic Record in the Flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2015.60.107",
            "key": "https://doi.org/10.6165/tai.2015.60.107",
            "value": {
                "id": "https-doi-org-10-6165-tai-2015-60-107",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New species of the genus ＂Cleisostoma＂ in the flora of Vietnam",
                    "url": "https://doi.org/10.6165/tai.2015.60.107",
                    "description": "A short review of the genus ＂Cleisostoma＂ in the flora of Vietnam is presented with 9 sections and 28 species among which 9 are locally endemic. Present data show the territory of Vietnam as the richest center of diversity for the genus. Two monotypic sections (＂Gastrochilopsis, Pterogyne＂) and three species (＂Cleisostoma lecongkietii, C. phitamii, C. tricornutum＂) are described as new for science, two species (＂C. subulatum, C. linearilobatum＂) are reported on the base of voucher specimens as a new record for the flora of Vietnam."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2015.60.107",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2015.60.107",
                    "fulltext": "New species of the genus ＂Cleisostoma＂ in the flora of Vietnam A short review of the genus ＂Cleisostoma＂ in the flora of Vietnam is presented with 9 sections and 28 species among which 9 are locally endemic. Present data show the territory of Vietnam as the richest center of diversity for the genus. Two monotypic sections (＂Gastrochilopsis, Pterogyne＂) and three species (＂Cleisostoma lecongkietii, C. phitamii, C. tricornutum＂) are described as new for science, two species (＂C. subulatum, C. linearilobatum＂) are reported on the base of voucher specimens as a new record for the flora of Vietnam. 10.6165/tai.2015.60.107 Leonid V. Averyanov Nguyen Thien Tich Nguyen Van Canh 60 3 107 116",
                    "fulltext_boosted": "New species of the genus ＂Cleisostoma＂ in the flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2015.60.169",
            "key": "https://doi.org/10.6165/tai.2015.60.169",
            "value": {
                "id": "https-doi-org-10-6165-tai-2015-60-169",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Newly Discovered Native Orchids of Taiwan (VIII)/臺灣新發現的野生蘭（VIII）",
                    "url": "https://doi.org/10.6165/tai.2015.60.169",
                    "description": "This report presents three new orchids in Taiwan, i.e., ＂Epipactis fascicularis＂ T.P. Lin, ＂Neottia piluchiensis＂ T.P. Lin, and ＂Platanthera nantousylvatica＂ T.P. Lin./本文介紹三種臺灣新發現的野生蘭（余氏鈴蘭、碧綠溪雙葉蘭、南投蜻蛉蘭）。"
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2015.60.169",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2015.60.169",
                    "fulltext": "Newly Discovered Native Orchids of Taiwan (VIII) 臺灣新發現的野生蘭（VIII） This report presents three new orchids in Taiwan, i.e., ＂Epipactis fascicularis＂ T.P. Lin, ＂Neottia piluchiensis＂ T.P. Lin, and ＂Platanthera nantousylvatica＂ T.P. Lin. 本文介紹三種臺灣新發現的野生蘭（余氏鈴蘭、碧綠溪雙葉蘭、南投蜻蛉蘭）。 10.6165/tai.2015.60.169 Tsan-Piao Lin 林讚標 60 4 169 174",
                    "fulltext_boosted": "Newly Discovered Native Orchids of Taiwan (VIII) 臺灣新發現的野生蘭（VIII）"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2015.60.169#creator-1",
            "key": "https://doi.org/10.6165/tai.2015.60.169#creator-1",
            "value": {
                "id": "https-doi-org-10-6165-tai-2015-60-169-creator-1",
                "type": "Person",
                "search_result_data": {
                    "name": "Tsan-Piao Lin/林讚標",
                    "url": "https://doi.org/10.6165/tai.2015.60.169#creator-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:35562-1",
                    "type": "Person",
                    "fulltext": "Tsan-Piao Lin 林讚標",
                    "fulltext_boosted": "Tsan-Piao Lin 林讚標"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2015.60.33",
            "key": "https://doi.org/10.6165/tai.2015.60.33",
            "value": {
                "id": "https-doi-org-10-6165-tai-2015-60-33",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Review of the Genus ＂Miguelia＂ (Orchidaceae) with a New Species, ＂M. cruenta＂, from Southern Vietnam",
                    "url": "https://doi.org/10.6165/tai.2015.60.33",
                    "description": "This review of the genus ＂Miguelia＂ Aver. includes a brief characterization of the genus, a key for species identification, appropriate taxonomic citation and synonyms for each species, and notes on ecology, phenology and distribution. ＂M. cruenta＂, discovered in southern Vietnam, is described and illustrated as a new species. The tentative relationship of the newly discovered species is briefly discussed."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2015.60.33",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2015.60.33",
                    "fulltext": "Review of the Genus ＂Miguelia＂ (Orchidaceae) with a New Species, ＂M. cruenta＂, from Southern Vietnam This review of the genus ＂Miguelia＂ Aver. includes a brief characterization of the genus, a key for species identification, appropriate taxonomic citation and synonyms for each species, and notes on ecology, phenology and distribution. ＂M. cruenta＂, discovered in southern Vietnam, is described and illustrated as a new species. The tentative relationship of the newly discovered species is briefly discussed. 10.6165/tai.2015.60.33 Leonid V. Averyanov Truong Ba Vuong 60 1 33 38",
                    "fulltext_boosted": "Review of the Genus ＂Miguelia＂ (Orchidaceae) with a New Species, ＂M. cruenta＂, from Southern Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2015.60.86",
            "key": "https://doi.org/10.6165/tai.2015.60.86",
            "value": {
                "id": "https-doi-org-10-6165-tai-2015-60-86",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "A New Species and Two New Records of ＂Ophiopogon＂ and ＂Peliosanthes＂ (Asparagaceae) in the Flora of Laos",
                    "url": "https://doi.org/10.6165/tai.2015.60.86",
                    "description": "One species of Peliosanthes from Laos, named P. irinae, is described as new to science. This species is quite unusual especially in having a thick fleshy inflorescence rachis to which sessile flowers are sparsely adpressed. Ophiopogon griffithii and Peliosanthes sinica are also recorded as new to the flora of Laos. Taxonomic accounts of these species including illustrations are provided."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2015.60.86",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2015.60.86",
                    "fulltext": "A New Species and Two New Records of ＂Ophiopogon＂ and ＂Peliosanthes＂ (Asparagaceae) in the Flora of Laos One species of Peliosanthes from Laos, named P. irinae, is described as new to science. This species is quite unusual especially in having a thick fleshy inflorescence rachis to which sessile flowers are sparsely adpressed. Ophiopogon griffithii and Peliosanthes sinica are also recorded as new to the flora of Laos. Taxonomic accounts of these species including illustrations are provided. 10.6165/tai.2015.60.86 Leonid V. Averyanov Noriyuki Tanaka Khang Sinh Nguyen Eugene L. Konstantinov 60 2 86 90",
                    "fulltext_boosted": "A New Species and Two New Records of ＂Ophiopogon＂ and ＂Peliosanthes＂ (Asparagaceae) in the Flora of Laos"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2016.61.201",
            "key": "https://doi.org/10.6165/tai.2016.61.201",
            "value": {
                "id": "https-doi-org-10-6165-tai-2016-61-201",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Laos and Vietnam",
                    "url": "https://doi.org/10.6165/tai.2016.61.201",
                    "description": "Three new species of Ophiopogon, O. alatus, O. erectus from N. Vietnam and O. patulus from NE. Laos, and three new species of Peliosanthes, P. inaperta from central Laos and P. kenhilloides, P. splendens from NW. Vietnam, are described with illustrations. These taxa are regarded as local endemics of the respective countries. Peliosanthes macrostegia is recorded as new to the flora of Vietnam. Data on distribution and ecological aspects of O. hayatae is added with photographic illustrations, because our knowledge on this species in Vietnam is still insufficient."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2016.61.201",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2016.61.201",
                    "fulltext": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Laos and Vietnam Three new species of Ophiopogon, O. alatus, O. erectus from N. Vietnam and O. patulus from NE. Laos, and three new species of Peliosanthes, P. inaperta from central Laos and P. kenhilloides, P. splendens from NW. Vietnam, are described with illustrations. These taxa are regarded as local endemics of the respective countries. Peliosanthes macrostegia is recorded as new to the flora of Vietnam. Data on distribution and ecological aspects of O. hayatae is added with photographic illustrations, because our knowledge on this species in Vietnam is still insufficient. 10.6165/tai.2016.61.201 Leonid V Averyanov Noriyuki Tanaka Khang Sinh Nguyen Tien Hiep Nguyen 61 3 201 217",
                    "fulltext_boosted": "New Species of Ophiopogon and Peliosanthes (Asparagaceae) from Laos and Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2016.61.319",
            "key": "https://doi.org/10.6165/tai.2016.61.319",
            "value": {
                "id": "https-doi-org-10-6165-tai-2016-61-319",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Species of Orchids (Orchidaceae) in the Flora of Vietnam",
                    "url": "https://doi.org/10.6165/tai.2016.61.319",
                    "description": "This paper summarizes results of joint efforts of professional botanists and orchid enthusiasts on studies of Vietnamese native orchids during years 2013-2016. It provides new original data about the discovery of 1 genus (Grammatophyllum Blume) and 29 orchid species new for the flora of Vietnam. Valid name, main synonyms, data on type, ecology, phenology, estimated IUCN Red List status, distribution, studied specimens, as well as brief taxonomic and biological notes are provided for each species and varieties. Eight species (Bidupia khangii, Bulbophyllum striatulum, B. tipula, Cleisostoma dorsisacculatum, Cymbidium repens, Dendrobium congianum, Flickingeria xanthocheila, Podochilus rotundipetala) and two varieties (Phreatia densiflora var. vietnamensis, P. formosana var. continentalis) are described as new for science. One combination (Bulbophyllum bicolor var. funingense) is proposed. An illustrated annotated list of all studied species and varieties is arranged in alphabetical order. Including present data, the known orchid flora of Vietnam comprises currently at least 1210 documented species from 172 genera."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2016.61.319",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2016.61.319",
                    "fulltext": "New Species of Orchids (Orchidaceae) in the Flora of Vietnam This paper summarizes results of joint efforts of professional botanists and orchid enthusiasts on studies of Vietnamese native orchids during years 2013-2016. It provides new original data about the discovery of 1 genus (Grammatophyllum Blume) and 29 orchid species new for the flora of Vietnam. Valid name, main synonyms, data on type, ecology, phenology, estimated IUCN Red List status, distribution, studied specimens, as well as brief taxonomic and biological notes are provided for each species and varieties. Eight species (Bidupia khangii, Bulbophyllum striatulum, B. tipula, Cleisostoma dorsisacculatum, Cymbidium repens, Dendrobium congianum, Flickingeria xanthocheila, Podochilus rotundipetala) and two varieties (Phreatia densiflora var. vietnamensis, P. formosana var. continentalis) are described as new for science. One combination (Bulbophyllum bicolor var. funingense) is proposed. An illustrated annotated list of all studied species and varieties is arranged in alphabetical order. Including present data, the known orchid flora of Vietnam comprises currently at least 1210 documented species from 172 genera. 10.6165/tai.2016.61.319 Ba Vuong Truong Van Canh Nguyen Leonid V Averyanov Quang Thinh Phan Van Duy Nong Khang Sinh Nguyen Tatiana V Maisak Phi Tam Nguyen Thien Tich Nguyen 61 4 319 354",
                    "fulltext_boosted": "New Species of Orchids (Orchidaceae) in the Flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2017.62.1",
            "key": "https://doi.org/10.6165/tai.2017.62.1",
            "value": {
                "id": "https-doi-org-10-6165-tai-2017-62-1",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Bulbophyllum sect. Hirtula in eastern Indochina",
                    "url": "https://doi.org/10.6165/tai.2017.62.1",
                    "description": "Modern taxonomic revision of Bulbophyllum sect Hirtula in the flora of eastern Indochina, including Cambodia, Laos and Vietnam based on all available collections and literature data reports 12 species (Bulbophyllum clipeibulbum, B. dasystachys, B. glabrichelia, B. nigrescens, B. nigripetalum, B. parviflorum, B. penicillium, B. phitamii, B. scaphiforme, B. secundum, B. setilabium, B. spadiciflorum), 2 of which represent new records for the studied flora (B. parviflorum, B. penicillium) and 3 (Bulbophyllum glabrichelia, B. phitamii, B. setilabium) are described as a new for science. For all accepted taxa the paper provides valid name and main synonyms with appropriate standard taxonomic references, data on type and other authentic materials, description, data on ecology, phenology, expected conservation status, distribution, notes on biology and taxonomy, list of all studied materials, as well as key for identification of species and their line and color illustrations."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2017.62.1",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2017.62.1",
                    "fulltext": "Bulbophyllum sect. Hirtula in eastern Indochina Modern taxonomic revision of Bulbophyllum sect Hirtula in the flora of eastern Indochina, including Cambodia, Laos and Vietnam based on all available collections and literature data reports 12 species (Bulbophyllum clipeibulbum, B. dasystachys, B. glabrichelia, B. nigrescens, B. nigripetalum, B. parviflorum, B. penicillium, B. phitamii, B. scaphiforme, B. secundum, B. setilabium, B. spadiciflorum), 2 of which represent new records for the studied flora (B. parviflorum, B. penicillium) and 3 (Bulbophyllum glabrichelia, B. phitamii, B. setilabium) are described as a new for science. For all accepted taxa the paper provides valid name and main synonyms with appropriate standard taxonomic references, data on type and other authentic materials, description, data on ecology, phenology, expected conservation status, distribution, notes on biology and taxonomy, list of all studied materials, as well as key for identification of species and their line and color illustrations. 10.6165/tai.2017.62.1 Leonid V Averyanov Khang Sinh Nguyen Van Duy Nong Van Canh Nguyen Ba Vuong Truong Tatiana V Maisak 62 1 1 23",
                    "fulltext_boosted": "Bulbophyllum sect. Hirtula in eastern Indochina"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2017.62.257",
            "key": "https://doi.org/10.6165/tai.2017.62.257",
            "value": {
                "id": "https-doi-org-10-6165-tai-2017-62-257",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Tupistra khasiana (Asparagaceae), a new species from Meghalaya, India",
                    "url": "https://doi.org/10.6165/tai.2017.62.257",
                    "description": "Tupistra khasiana D.K.Roy, A.A.Mao & Aver. (Asparagaceae), a new species from Meghalaya, India is described and illustrated. It differs from similar congeners, T. pingbianensis, T. fungilliformis and T. tupistroides in having creeping rhizomatous stem, down curved peduncle, smaller, thick, fleshy, bract, to 3 mm long, obscurely tri-dentate bracteole, to 2 mm long, ca. 2 mm broad, comparatively shorter perianth, to 8.5 mm long, with externally light green tube and smaller lobes, 3.5-4 mm long, shorter style, 5 mm long and in dark purple stigma, with distinctly undulated margin. Key to the Indian Tupistra is given."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2017.62.257",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2017.62.257",
                    "fulltext": "Tupistra khasiana (Asparagaceae), a new species from Meghalaya, India Tupistra khasiana D.K.Roy, A.A.Mao & Aver. (Asparagaceae), a new species from Meghalaya, India is described and illustrated. It differs from similar congeners, T. pingbianensis, T. fungilliformis and T. tupistroides in having creeping rhizomatous stem, down curved peduncle, smaller, thick, fleshy, bract, to 3 mm long, obscurely tri-dentate bracteole, to 2 mm long, ca. 2 mm broad, comparatively shorter perianth, to 8.5 mm long, with externally light green tube and smaller lobes, 3.5-4 mm long, shorter style, 5 mm long and in dark purple stigma, with distinctly undulated margin. Key to the Indian Tupistra is given. 10.6165/tai.2017.62.257 Dilip Kumar Roy Ashiho A Mao Leonid V Averyanov 62 3 257 260",
                    "fulltext_boosted": "Tupistra khasiana (Asparagaceae), a new species from Meghalaya, India"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2018.63.119",
            "key": "https://doi.org/10.6165/tai.2018.63.119",
            "value": {
                "id": "https-doi-org-10-6165-tai-2018-63-119",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Orchids (Orchidaceae: Cymbidieae and Vandeae) in the Flora of Vietnam",
                    "url": "https://doi.org/10.6165/tai.2018.63.119",
                    "description": "The paper continues publication of new original data on orchid diversity in Vietnam (tribes Cymbidieae and Vandeae) obtained in 2016-2018. It includes data on 2 genera and 10 species new for the flora of Vietnam. Among them, six species are new to science (Ascocentrum hienii, Biermannia canhii, Cymbidium tamphianum, Gastrochilus setosus, Malleola luongii, Robiquetia orlovii). Four other species are found on the territory of Vietnam for the first time (Bogoria raciborskii, Lesliea mirabilis, Pennilabium struthio, Uncifera obtusifolia). Two genera, Bogoria and Lesliea, are newly recorded for the flora of Vietnam. One new nomenclature combination (Ascocentropsis malipoensis), one new name (Ascocentropsis yunnanensis) and one lectotype (for Uncifera obtusifolia) are proposed. When the new data presented in this paper are included, the known orchid flora of Vietnam comprises about 1220 documented species from 174 genera."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2018.63.119",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2018.63.119",
                    "fulltext": "New Orchids (Orchidaceae: Cymbidieae and Vandeae) in the Flora of Vietnam The paper continues publication of new original data on orchid diversity in Vietnam (tribes Cymbidieae and Vandeae) obtained in 2016-2018. It includes data on 2 genera and 10 species new for the flora of Vietnam. Among them, six species are new to science (Ascocentrum hienii, Biermannia canhii, Cymbidium tamphianum, Gastrochilus setosus, Malleola luongii, Robiquetia orlovii). Four other species are found on the territory of Vietnam for the first time (Bogoria raciborskii, Lesliea mirabilis, Pennilabium struthio, Uncifera obtusifolia). Two genera, Bogoria and Lesliea, are newly recorded for the flora of Vietnam. One new nomenclature combination (Ascocentropsis malipoensis), one new name (Ascocentropsis yunnanensis) and one lectotype (for Uncifera obtusifolia) are proposed. When the new data presented in this paper are included, the known orchid flora of Vietnam comprises about 1220 documented species from 174 genera. 10.6165/tai.2018.63.119 Van Khang Nguyen Quang Diep Dinh Hong Son Le Van Canh Nguyen Ba Vuong Truong Tatiana V Maisak Hong Truong Luu Khang Sinh Nguyen Hoang Tuan Nguyen Xuan Canh Chu Leonid V Averyanov Gioi Tran 63 2 119 138",
                    "fulltext_boosted": "New Orchids (Orchidaceae: Cymbidieae and Vandeae) in the Flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2018.63.195",
            "key": "https://doi.org/10.6165/tai.2018.63.195",
            "value": {
                "id": "https-doi-org-10-6165-tai-2018-63-195",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "New Orchids (Orchidaceae: Epidendroideae and Vandoideae) in the Flora of Vietnam",
                    "url": "https://doi.org/10.6165/tai.2018.63.195",
                    "description": "The paper continues our recent publication of new original data on orchid diversity in Vietnam (Averyanov et al., 2018a-c) obtained in 2016-2017. It includes data on 5 orchid species new for science (Calanthe nguyenthinhii Aver., Dendrobium truongcuongii Aver. & Canh, Gastrodia khangii Aver., Nephelaphyllum thaovyae Aver. & Canh and Podochilus truongtamii Aver. & Vuong) and 15 species, new for the flora of Vietnam (Calanthe ceciliae, Dendrobium eriiflorum, D. griffithianum, D. hekouense, D. minusculum, D. stuposum, D. xichouense, Eria lancifolia, E. xanthocheila, Geodorum terrestre, Liparis condylobulbon, L. tenuis, Luisia teres, Pomatocalpa maculosum, Porpax ustulata). Annotated species list includes the valid name, synonyms, type, citations of relevant taxonomic regional publications, data on ecology, phenology and distribution, estimated IUCN Red List status, studied specimens, brief taxonomic notes, and illustrations for each recorded species. Lectotypes for two species, Liparis tenuis, and Dendrobium exsculptum are proposed. When the new data presented in this paper are included, the documented orchid flora of Vietnam reaches at least 1243 species."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2018.63.195",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2018.63.195",
                    "fulltext": "New Orchids (Orchidaceae: Epidendroideae and Vandoideae) in the Flora of Vietnam The paper continues our recent publication of new original data on orchid diversity in Vietnam (Averyanov et al., 2018a-c) obtained in 2016-2017. It includes data on 5 orchid species new for science (Calanthe nguyenthinhii Aver., Dendrobium truongcuongii Aver. & Canh, Gastrodia khangii Aver., Nephelaphyllum thaovyae Aver. & Canh and Podochilus truongtamii Aver. & Vuong) and 15 species, new for the flora of Vietnam (Calanthe ceciliae, Dendrobium eriiflorum, D. griffithianum, D. hekouense, D. minusculum, D. stuposum, D. xichouense, Eria lancifolia, E. xanthocheila, Geodorum terrestre, Liparis condylobulbon, L. tenuis, Luisia teres, Pomatocalpa maculosum, Porpax ustulata). Annotated species list includes the valid name, synonyms, type, citations of relevant taxonomic regional publications, data on ecology, phenology and distribution, estimated IUCN Red List status, studied specimens, brief taxonomic notes, and illustrations for each recorded species. Lectotypes for two species, Liparis tenuis, and Dendrobium exsculptum are proposed. When the new data presented in this paper are included, the documented orchid flora of Vietnam reaches at least 1243 species. 10.6165/tai.2018.63.195 Leonid V Averyanov Phi Tam Nguyen Xuan Canh Chu Van Canh Nguyen Hoang Tuan Nguyen Ba Vuong Truong Sinh Khang Nguyen Tatiana V Maisak Hiep Tien Nguyen Duc Nam Bui 63 3 195 219",
                    "fulltext_boosted": "New Orchids (Orchidaceae: Epidendroideae and Vandoideae) in the Flora of Vietnam"
                }
            }
        },
        {
            "id": "https://doi.org/10.6165/tai.2018.63.389",
            "key": "https://doi.org/10.6165/tai.2018.63.389",
            "value": {
                "id": "https-doi-org-10-6165-tai-2018-63-389",
                "type": "ScholarlyArticle",
                "search_result_data": {
                    "name": "Chiloschista pulchella (Orchidaceae: Aeridinae) new orchid species from Lao PDR",
                    "url": "https://doi.org/10.6165/tai.2018.63.389",
                    "description": "The new species, Chiloschista pulchella (Orchidaceae: Aeridinae) was discovered in Hin Nam No Nature Protected Area, Khammoune Province of the central Laos. The paper provides detailed description and illustration of this species, which is identified as a local endemic of karstic rocky limestone of the northern part of the protected area. It differs from all known congeners in the thin lip, median lip lobe dissected into two small subulate lobules, as well as in large purple blotches on the lip side-lobes never found in other species of this genus. The newly discovered plant represents interest for cultivation as an ornamental plant and needs protection in its natural habitats."
                },
                "search_data": {
                    "cluster_id": "https://doi.org/10.6165/tai.2018.63.389",
                    "type": "ScholarlyArticle",
                    "doi": "10.6165/tai.2018.63.389",
                    "fulltext": "Chiloschista pulchella (Orchidaceae: Aeridinae) new orchid species from Lao PDR The new species, Chiloschista pulchella (Orchidaceae: Aeridinae) was discovered in Hin Nam No Nature Protected Area, Khammoune Province of the central Laos. The paper provides detailed description and illustration of this species, which is identified as a local endemic of karstic rocky limestone of the northern part of the protected area. It differs from all known congeners in the thin lip, median lip lobe dissected into two small subulate lobules, as well as in large purple blotches on the lip side-lobes never found in other species of this genus. The newly discovered plant represents interest for cultivation as an ornamental plant and needs protection in its natural habitats. 10.6165/tai.2018.63.389 Leonid V Averyanov Khang Sinh Nguyen Tatiana V Maisak 63 4 389 392",
                    "fulltext_boosted": "Chiloschista pulchella (Orchidaceae: Aeridinae) new orchid species from Lao PDR"
                }
            }
        },
        {
            "id": "https://orcid.org/0000-0001-5171-4140",
            "key": "https://orcid.org/0000-0001-5171-4140",
            "value": {
                "id": "https-orcid-org-0000-0001-5171-4140",
                "type": "Person",
                "search_result_data": {
                    "name": "khang Sinh Nguyen",
                    "url": "https://orcid.org/0000-0001-5171-4140"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0001-5171-4140",
                    "type": "Person",
                    "fulltext": "khang Sinh Nguyen",
                    "fulltext_boosted": "khang Sinh Nguyen"
                }
            }
        },
        {
            "id": "https://orcid.org/0000-0001-8031-2925",
            "key": "https://orcid.org/0000-0001-8031-2925",
            "value": {
                "id": "https-orcid-org-0000-0001-8031-2925",
                "type": "Person",
                "search_result_data": {
                    "name": "Leonid Averyanov",
                    "url": "https://orcid.org/0000-0001-8031-2925"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0001-8031-2925",
                    "type": "Person",
                    "fulltext": "Leonid Averyanov Lo",
                    "fulltext_boosted": "Leonid Averyanov"
                }
            }
        },
        {
            "id": "https://orcid.org/0000-0003-2857-3583",
            "key": "https://orcid.org/0000-0003-2857-3583",
            "value": {
                "id": "https-orcid-org-0000-0003-2857-3583",
                "type": "Person",
                "search_result_data": {
                    "name": "Anneke Veenstra",
                    "url": "https://orcid.org/0000-0003-2857-3583"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0003-2857-3583",
                    "type": "Person",
                    "fulltext": "Anneke Veenstra Anneke A. Veenstra Anneke Veenstra-Quah",
                    "fulltext_boosted": "Anneke Veenstra"
                }
            }
        },
        {
            "id": "https://www.gbif.org/occurrence/1024567444",
            "key": "https://www.gbif.org/occurrence/1024567444",
            "value": {
                "id": "https-www-gbif-org-occurrence-1024567444",
                "type": "Occurrence",
                "search_result_data": {
                    "name": "HOLOTYPE of Pholidota yongii J.J.Wood [family ORCHIDACEAE] (K)",
                    "url": "https://www.gbif.org/occurrence/1024567444"
                },
                "search_data": {
                    "cluster_id": "https://www.gbif.org/occurrence/1024567444",
                    "type": "Occurrence",
                    "fulltext": "HOLOTYPE of Pholidota yongii J.J.Wood [family ORCHIDACEAE] (K) Kuching district, Mount Penrissen 78095.000 140 Yong, R.",
                    "fulltext_boosted": "HOLOTYPE of Pholidota yongii J.J.Wood [family ORCHIDACEAE] (K)"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:authors:14590-1",
            "key": "urn:lsid:ipni.org:authors:14590-1",
            "value": {
                "id": "urn-lsid-ipni-org-authors-14590-1",
                "type": "Person",
                "search_result_data": {
                    "name": "Leonid Vladimirovich Averyanov",
                    "url": "urn:lsid:ipni.org:authors:14590-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:14590-1",
                    "type": "Person",
                    "fulltext": "Leonid Vladimirovich Averyanov",
                    "fulltext_boosted": "Leonid Vladimirovich Averyanov"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:authors:35562-1",
            "key": "urn:lsid:ipni.org:authors:35562-1",
            "value": {
                "id": "urn-lsid-ipni-org-authors-35562-1",
                "type": "Person",
                "search_result_data": {
                    "name": "Tsan Piao Lin",
                    "url": "urn:lsid:ipni.org:authors:35562-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:35562-1",
                    "type": "Person",
                    "fulltext": "Tsan Piao Lin",
                    "fulltext_boosted": "Tsan Piao Lin"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:authors:36928-1",
            "key": "urn:lsid:ipni.org:authors:36928-1",
            "value": {
                "id": "urn-lsid-ipni-org-authors-36928-1",
                "type": "Person",
                "search_result_data": {
                    "name": "Paul Abel Ormerod",
                    "url": "urn:lsid:ipni.org:authors:36928-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:36928-1",
                    "type": "Person",
                    "fulltext": "Paul Abel Ormerod",
                    "fulltext_boosted": "Paul Abel Ormerod"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:authors:38863-1",
            "key": "urn:lsid:ipni.org:authors:38863-1",
            "value": {
                "id": "urn-lsid-ipni-org-authors-38863-1",
                "type": "Person",
                "search_result_data": {
                    "name": "Hans-Jürgen Tillich",
                    "url": "urn:lsid:ipni.org:authors:38863-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:authors:38863-1",
                    "type": "Person",
                    "fulltext": "Hans-Jürgen Tillich",
                    "fulltext_boosted": "Hans-Jürgen Tillich"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:names:77127392-1",
            "key": "urn:lsid:ipni.org:names:77127392-1",
            "value": {
                "id": "urn-lsid-ipni-org-names-77127392-1",
                "type": "TaxonName",
                "search_result_data": {
                    "name": "Pholidota yongii J.J.Wood",
                    "url": "urn:lsid:ipni.org:names:77127392-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:names:77127392-1",
                    "type": "TaxonName",
                    "fulltext": "Pholidota yongii J.J.Wood",
                    "fulltext_boosted": "Pholidota yongii J.J.Wood"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:names:77152027-1",
            "key": "urn:lsid:ipni.org:names:77152027-1",
            "value": {
                "id": "urn-lsid-ipni-org-names-77152027-1",
                "type": "TaxonName",
                "search_result_data": {
                    "name": "Platanthera nantousylvatica T.P.Lin",
                    "url": "urn:lsid:ipni.org:names:77152027-1"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:names:77152027-1",
                    "type": "TaxonName",
                    "fulltext": "Platanthera nantousylvatica T.P.Lin",
                    "fulltext_boosted": "Platanthera nantousylvatica T.P.Lin"
                }
            }
        },
        {
            "id": "urn:lsid:ipni.org:names:77152027-1#3",
            "key": "urn:lsid:ipni.org:names:77152027-1#3",
            "value": {
                "id": "urn-lsid-ipni-org-names-77152027-1-3",
                "type": "NomenclaturalType",
                "search_result_data": {
                    "name": "Po-Neng Shen s.n., TAI (holo)",
                    "url": "urn:lsid:ipni.org:names:77152027-1#3"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:ipni.org:names:77152027-1#3",
                    "type": "NomenclaturalType",
                    "fulltext": "Po-Neng Shen s.n., TAI (holo)",
                    "fulltext_boosted": "Po-Neng Shen s.n., TAI (holo)"
                }
            }
        },
        {
            "id": "urn:lsid:zoobank.org:author:07A305E3-F9F0-483E-9E33-88E2CB9CBFAD",
            "key": "urn:lsid:zoobank.org:author:07A305E3-F9F0-483E-9E33-88E2CB9CBFAD",
            "value": {
                "id": "urn-lsid-zoobank-org-author-07A305E3-F9F0-483E-9E33-88E2CB9CBFAD",
                "type": "Person",
                "search_result_data": {
                    "name": "Anneke A. Veenstra",
                    "url": "urn:lsid:zoobank.org:author:07A305E3-F9F0-483E-9E33-88E2CB9CBFAD"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0003-2857-3583",
                    "type": "Person",
                    "fulltext": "Anneke A. Veenstra",
                    "fulltext_boosted": "Anneke A. Veenstra"
                }
            }
        },
        {
            "id": "urn:lsid:zoobank.org:author:A3D8D082-8394-48F9-8926-CC2B3CDF56B1",
            "key": "urn:lsid:zoobank.org:author:A3D8D082-8394-48F9-8926-CC2B3CDF56B1",
            "value": {
                "id": "urn-lsid-zoobank-org-author-A3D8D082-8394-48F9-8926-CC2B3CDF56B1",
                "type": "Person",
                "search_result_data": {
                    "name": "Anneke Veenstra",
                    "url": "urn:lsid:zoobank.org:author:A3D8D082-8394-48F9-8926-CC2B3CDF56B1"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0003-2857-3583",
                    "type": "Person",
                    "fulltext": "Anneke Veenstra",
                    "fulltext_boosted": "Anneke Veenstra"
                }
            }
        },
        {
            "id": "urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A",
            "key": "urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A",
            "value": {
                "id": "urn-lsid-zoobank-org-author-B95E5A4D-2E3D-4760-8452-7D5A7884F53A",
                "type": "Person",
                "search_result_data": {
                    "name": "Cuong Huynh",
                    "url": "urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A"
                },
                "search_data": {
                    "cluster_id": "urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A",
                    "type": "Person",
                    "fulltext": "Cuong Huynh",
                    "fulltext_boosted": "Cuong Huynh"
                }
            }
        },
        {
            "id": "urn:lsid:zoobank.org:author:CED1B53E-6D79-4796-80A4-79A6740AEBF1",
            "key": "urn:lsid:zoobank.org:author:CED1B53E-6D79-4796-80A4-79A6740AEBF1",
            "value": {
                "id": "urn-lsid-zoobank-org-author-CED1B53E-6D79-4796-80A4-79A6740AEBF1",
                "type": "Person",
                "search_result_data": {
                    "name": "Anneke A.veenstra",
                    "url": "urn:lsid:zoobank.org:author:CED1B53E-6D79-4796-80A4-79A6740AEBF1"
                },
                "search_data": {
                    "cluster_id": "https://orcid.org/0000-0003-2857-3583",
                    "type": "Person",
                    "fulltext": "Anneke A.veenstra",
                    "fulltext_boosted": "Anneke A.veenstra"
                }
            }
        }
    ]
}';

$obj = json_decode($json);

print_r($obj);

foreach ($obj->rows as $row)
{
	$elastic_doc = new stdclass;
	$elastic_doc->doc = $row->value;
	$elastic_doc->doc_as_upsert = true;
	$elastic->send('POST',  '_doc/' . urlencode($elastic_doc->doc->id). '/_update', json_encode($elastic_doc));					
}
	
?>
