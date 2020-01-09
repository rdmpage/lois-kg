<?xml version='1.0' encoding='utf-8'?>
<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform' xmlns:xlink="http://www.w3.org/1999/xlink"

xmlns:ce="http://www.elsevier.com/xml/common/dtd" 
xmlns:sb="http://www.elsevier.com/xml/common/struct-bib/dtd" 
>

<xsl:output method='html' version='1.0' encoding='utf-8' indent='yes'/>

<xsl:template match="/">

	<xsl:apply-templates select="//ce:bib-reference"/>

</xsl:template>

<!--

                  <ce:bib-reference id="bb2010">
                     <ce:label>Meve et al., 2017</ce:label>
                     <sb:reference id="rf3333">
                        <sb:contribution langtype="en">
                           <sb:authors>
                              <sb:author>
                                 <ce:given-name>U.</ce:given-name>
                                 <ce:surname>Meve</ce:surname>
                              </sb:author>
                              <sb:author>
                                 <ce:given-name>A.</ce:given-name>
                                 <ce:surname>Heiduk</ce:surname>
                              </sb:author>
                              <sb:author>
                                 <ce:given-name>S.</ce:given-name>
                                 <ce:surname>Liede-Schumann</ce:surname>
                              </sb:author>
                           </sb:authors>
                           <sb:title>
                              <sb:maintitle>Origin and early evolution of Ceropegieae (Apocynaceae-Asclepiadoideae)</sb:maintitle>
                           </sb:title>
                        </sb:contribution>
                        <sb:host>
                           <sb:issue>
                              <sb:series>
                                 <sb:title>
                                    <sb:maintitle>Systematics and Biodiversity</sb:maintitle>
                                 </sb:title>
                                 <sb:volume-nr>15</sb:volume-nr>
                              </sb:series>
                              <sb:date>2017</sb:date>
                           </sb:issue>
                           <sb:pages>
                              <sb:first-page>143</sb:first-page>
                              <sb:last-page>155</sb:last-page>
                           </sb:pages>
                        </sb:host>
                     </sb:reference>
                  </ce:bib-reference>


-->

<xsl:template match="ce:bib-reference">
<xsl:apply-templates match="ce:label" />

<li>
<xsl:value-of select="@id" />
bib-reference
<xsl:apply-templates match="sb:reference" />
bib-reference

</li>
</xsl:template>

<xsl:template match="sb:reference">
reference
<xsl:value-of select="sb:contribution/sb:title/sb:maintitle" />
reference
</xsl:template>

<xsl:template match="ce:label">

</xsl:template>


</xsl:stylesheet>
