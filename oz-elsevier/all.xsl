<?xml version='1.0' encoding='utf-8'?>
<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform' xmlns:xlink="http://www.w3.org/1999/xlink"

xmlns:ce="http://www.elsevier.com/xml/common/dtd" 
xmlns:sb="http://www.elsevier.com/xml/common/struct-bib/dtd" 
>

<xsl:output method='html' version='1.0' encoding='utf-8' indent='yes'/>

<xsl:template match="/">

	<!-- <xsl:apply-templates /> -->
	
	<xsl:apply-templates match="//tail" />
	

</xsl:template>


<xsl:template match="head">
</xsl:template>

<xsl:template match="body">
</xsl:template>

<xsl:template match="tail">
	<ol>
	<xsl:apply-templates match="//ce:bib-reference" />
	</ol>
</xsl:template>

<!--
<xsl:template match="ce:section-title">
<h2>
<xsl:value-of select="." />
</h2>
<xsl:apply-templates />
</xsl:template>

<xsl:template match="ce:para">
<p>
<xsl:apply-templates />
</p>
</xsl:template>


<xsl:template match="ce:italic">
<i>
<xsl:apply-templates />
</i>
</xsl:template>

<xsl:template match="ce:bold">
<b>
<xsl:apply-templates />
</b>
</xsl:template>

-->

<xsl:template match="ce:bib-reference">
	<xsl:apply-templates match="ce:label" />

	<li>
		<xsl:value-of select="@id" />
		<xsl:apply-templates match="sb:reference" />
	</li>
</xsl:template>

<xsl:template match="sb:reference">
	<xsl:value-of select="sb:contribution/sb:title/sb:maintitle" />
	<xsl:apply-templates match="sb:host" /> 
</xsl:template>

<xsl:template match="sb:host">
	<xsl:apply-templates match="sb:issue" />
	<xsl:apply-templates match="/sb:pages" />
	<hr />
</xsl:template>

<xsl:template match="sb:issue">
	<xsl:apply-templates match="sb:series" />
	<hr />
</xsl:template>

<xsl:template match="sb:series">
	<xsl:apply-templates match="sb:title" />
	<xsl:apply-templates match="sb:volume-nr" />
</xsl:template>

<xsl:template match="sb:title">
	<i><xsl:value-of select="sb:maintitle" /></i>
</xsl:template>

<xsl:template match="sb:volume-nr">
	<b><xsl:value-of select="." /></b>
</xsl:template>



<xsl:template match="sb:pages">
	<xsl:if test="sb:first-page">
	<xsl:value-of select="." />
	</xsl:if>


	--
	<xsl:if test="sb:last-page">
	<xsl:value-of select="." />
	</xsl:if>
</xsl:template>

<xsl:template match="ce:label">

</xsl:template>





</xsl:stylesheet>
