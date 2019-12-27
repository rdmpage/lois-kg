<?xml version='1.0' encoding='utf-8'?>
<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:tp="http://www.plazi.org/taxpub"

exclude-result-prefixes="xlink mml tp"
>
<xsl:output method='html' version='1.0' encoding='utf-8' indent='yes'/>

<xsl:param name="work" />  

	<!-- shared -->
	
	<!-- string replace -->    
    <xsl:variable name="smallcase" select="'abcdefghijklmnopqrstuvwxyz'" />
    <xsl:variable name="uppercase" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'" />
    <!-- from http://aspn.activestate.com/ASPN/Cookbook/XSLT/Recipe/65426 -->
    <!-- reusable replace-string function -->
    <xsl:template name="replace-string">
        <xsl:param name="text"/>
        <xsl:param name="from"/>
        <xsl:param name="to"/>
        <xsl:choose>
            <xsl:when test="contains($text, $from)">
                <xsl:variable name="before" select="substring-before($text, $from)"/>
                <xsl:variable name="after" select="substring-after($text, $from)"/>
                <xsl:variable name="prefix" select="concat($before, $to)"/>
                <xsl:value-of select="$before"/>
                <xsl:value-of select="$to"/>
                <xsl:call-template name="replace-string">
                    <xsl:with-param name="text" select="$after"/>
                    <xsl:with-param name="from" select="$from"/>
                    <xsl:with-param name="to" select="$to"/>
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$text"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <!-- summarise article metadata -->
     <xsl:template match="front">
        <div>
        
        
            <div>
                <xsl:value-of select="journal-meta/journal-title-group/journal-title" />
                <xsl:text> </xsl:text>
                <xsl:if test="article-meta/pub-date/day">
                    <xsl:value-of select="article-meta/pub-date/day" />
                    <xsl:text> </xsl:text>
                </xsl:if>
                <xsl:if test="article-meta/pub-date/month">
                    <xsl:choose>
                        <xsl:when test="article-meta/pub-date/month = 1">
                            <xsl:text>January</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 2">
                            <xsl:text>February</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 3">
                            <xsl:text>March</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 4">
                            <xsl:text>April</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 5">
                            <xsl:text>May</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 6">
                            <xsl:text>June</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 7">
                            <xsl:text>July</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 8">
                            <xsl:text>August</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 9">
                            <xsl:text>September</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 10">
                            <xsl:text>October</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 11">
                            <xsl:text>November</xsl:text>
                        </xsl:when>
                        <xsl:when test="article-meta/pub-date/month = 12">
                            <xsl:text>December</xsl:text>
                        </xsl:when>
                    </xsl:choose>
                    <xsl:text> </xsl:text>
                </xsl:if>
                <xsl:if test="article-meta/pub-date/year">
                    <xsl:value-of select="article-meta/pub-date/year" />
                </xsl:if>
                <xsl:text> </xsl:text>
                <xsl:value-of select="article-meta/volume" />
                <xsl:if test="article-meta/issue">
                    <xsl:text>(</xsl:text>
                    <xsl:value-of select="article-meta/issue" />
                    <xsl:text>)</xsl:text>
                </xsl:if>
                <!-- pagination -->
                <xsl:if test="article-meta/fpage">
                    <xsl:text>:</xsl:text>
                    <xsl:value-of select="article-meta/fpage" />
                    <xsl:if test="article-meta/lpage">
                        <xsl:text>-</xsl:text>
                        <xsl:value-of select="article-meta/lpage" />
                    </xsl:if>
                </xsl:if>
                <!-- article number -->
                <xsl:if test="article-meta/article-id[@pub-id-type='publisher-id']">
                    <xsl:text> </xsl:text>
                    <xsl:value-of select="article-meta/article-id[@pub-id-type='publisher-id']" />
                </xsl:if>
            </div>
            
            <h1>
                <!-- https://stackoverflow.com/questions/701723/rendering-html-tags-from-within-cdata-tag-in-xsl -->
                <xsl:value-of select="//article-title" disable-output-escaping="yes" />
            </h1>
            
            
            
            <xsl:apply-templates select="//contrib-group" />
			<!-- comment out as we will do this elswhere in the web page
            <ul>
                <xsl:apply-templates select="//article-id" />
                <xsl:apply-templates select="//self-uri[@content-type='lsid']" />
            </ul>
            -->
        </div>
    </xsl:template>    
    
    <!-- abstract -->
    <xsl:template match="abstract">
        <div>
            <xsl:apply-templates select="*" />
        </div>
    </xsl:template>
    
    <!-- authors -->
    <xsl:template match="//contrib-group">
        <div class="authors">
            <xsl:apply-templates select="contrib" />
        </div>
    </xsl:template>
    
    <!-- contributors -->
    <xsl:template match="contrib">
        <xsl:if test="@contrib-type='author'">
        	<!--
            <xsl:if test="position() != 1">
                <xsl:text>, </xsl:text>
            </xsl:if>
            -->
			<xsl:if test="position() != 1">
                <xsl:text> </xsl:text>
            </xsl:if>            
                       
            <xsl:apply-templates select="name" />
            <xsl:apply-templates select="string-name" />
            <xsl:apply-templates select="contrib-id" />
        </xsl:if>
    </xsl:template>
    
    <!-- person's name -->
    <xsl:template match="name | string-name">
        <xsl:if test="string-name">
            <xsl:value-of select="string-name" />
        </xsl:if>
        <xsl:if test="given-names">
            <xsl:value-of select="given-names" />
            <xsl:text> </xsl:text>
        </xsl:if>
        <xsl:if test="surname">
            <xsl:value-of select="surname" />
        </xsl:if>
    </xsl:template>
    
    <!-- contrib-id e.g. ORCID -->
    <!--
    <xsl:template match="contrib-id">
        <xsl:if test="@contrib-id-type = 'orcid'">
        	<xsl:text> </xsl:text>
           <a>
           		<xsl:attribute name="href">
            		<xsl:value-of select="." />
            	</xsl:attribute>
            	<img src="images/orcid_24x24.gif" />
        	</a>
        </xsl:if>
    </xsl:template>
    -->
    
    

<!-- -->

<xsl:template match="/">
<html>
<head>
<style>
h1 {
	line-height:1.2em;
}
</style>
</head>
<body style="font-family:sans-serif;padding:1em;line-height:1.5em;">

    <div>

    	 <xsl:apply-templates select="//front" />
    	 
        <xsl:if test="//front/article-meta/abstract">
            <xsl:apply-templates select="//front/article-meta/abstract" />
        </xsl:if>
    	 
    	 <xsl:apply-templates select="//body" />
    	 <xsl:apply-templates select="//back" />

	
	<!-- Biodiversity Data Journal -->
	<xsl:apply-templates select="//floats-group"/>
	
	</div>
	</body>
</html>	

</xsl:template>


    <xsl:template match="//body">
        <xsl:apply-templates select="sec"/>
         <!-- <xsl:apply-templates /> -->
         
           <!-- page image thumbnails -->
            <xsl:if test="//supplementary-material/graphic">
					<p>
						
							<a>
								<xsl:attribute name="href">
									<xsl:text>work/</xsl:text>
									<xsl:value-of select="$work" />
									<xsl:text>/full</xsl:text>
								</xsl:attribute>
							<xsl:text>Full text is available.</xsl:text>
							</a>
						
						<!--
						<a>
							<xsl:attribute name="href">
							<xsl:text>https://archive.org/download/biostor-</xsl:text>
							<xsl:value-of select="//article-id[@pub-id-type=&amp;quot;biostor&amp;quot;]"/><xsl:text>/biostor-</xsl:text><xsl:value-of select="//article-id[@pub-id-type=&amp;quot;biostor&amp;quot;]"/>
							<xsl:text>.pdf</xsl:text>
							</xsl:attribute>
							<xsl:text>complete article</xsl:text>
						</a>
						
						<xsl:text>.</xsl:text> 
						<xsl:if test="//back/ref-list">
							<xsl:text>Links are also available for </xsl:text>
							<a href="#reference-sec">Selected References</a>
							<xsl:text>.</xsl:text>
						</xsl:if> -->
					</p>
					
                <div>
                    <xsl:apply-templates select="//supplementary-material/graphic" />
                </div>
            </xsl:if>         
         
         
    </xsl:template>
    
    <xsl:template match="//back">
        <xsl:apply-templates select="ack"/>
        <xsl:apply-templates select="ref-list"/>
    </xsl:template>
    
    <xsl:template match="sec">
        <xsl:apply-templates />
    </xsl:template>
    
    <!-- tp -->
    <xsl:template match="tp:taxon-treatment">
    	<div> <!-- style="background-color:rgb(242,242,242);border-left: 5px solid red;margin-bottom:20px;padding-left:4px;"> -->
    	
    	<!-- construct a name for this element based on IPNI id so we can link to it -->
    	<xsl:choose>
    		<!-- <object-id content-type="ipni" xlink:type="simple">urn:lsid:ipni.org:names:77153386-1</object-id> -->
    		<xsl:when test='tp:nomenclature/tp:taxon-name/object-id[@content-type="ipni"]'>
    			|<xsl:value-of select="tp:nomenclature/tp:taxon-name/object-id" />|
    		</xsl:when> 

			<!-- <object-id xlink:type="simple">urn:lsid:ipni.org:names:77111569-1</object-id> -->
    		<xsl:when test='contains(tp:nomenclature/tp:taxon-name/object-id, "urn:lsid:ipni.org")'>
    			+<xsl:value-of select="tp:nomenclature/tp:taxon-name/object-id" />+
    		</xsl:when> 

			<xsl:otherwise>
			</xsl:otherwise>    	
		</xsl:choose>
    	   	
        <xsl:apply-templates />
       
        </div>
    </xsl:template>
    

    <!-- basic elements -->
    <xsl:template match="p"><p><xsl:apply-templates /></p></xsl:template>
    <xsl:template match="italic"><i><xsl:apply-templates /></i></xsl:template>
    <xsl:template match="bold"><b><xsl:apply-templates /></b></xsl:template>
    
    <!-- cross refs -->
    <xsl:template match="xref">
    	<xsl:choose>
    		<xsl:when test="@ref-type='bibr'">
				<a> 
					<xsl:attribute name="href">
						<xsl:text>#</xsl:text>
						<xsl:value-of select="@rid" />
					</xsl:attribute>
					<xsl:apply-templates />
				</a>
			</xsl:when>
			
   			<xsl:when test="@ref-type='fig'">
				<a> 
					<xsl:attribute name="href">
						<xsl:text>#</xsl:text>
						<xsl:value-of select="@rid" />
					</xsl:attribute>
					<xsl:apply-templates />
				</a>
			</xsl:when>
			
   			<xsl:when test="@ref-type='table'">
				<a> 
					<xsl:attribute name="href">
						<xsl:text>#</xsl:text>
						<xsl:value-of select="@rid" />
					</xsl:attribute>
					<xsl:apply-templates />
				</a>
			</xsl:when>
			
			
			<xsl:otherwise>    		
					<xsl:apply-templates />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<!-- links to data -->
    <xsl:template match="ext-link">
    	<xsl:choose>
    		<xsl:when test="ext-link-type='gen'">
				<!-- <span style="background-color:blue;color:white;"> -->
					<xsl:apply-templates />
				<!-- </span> -->
			</xsl:when>
			<xsl:otherwise>    		
				<!-- <span style="background-color:green;color:white;"> -->
					<xsl:apply-templates />
				<!-- </span> -->
			</xsl:otherwise>
		</xsl:choose>
    </xsl:template>
	
    <!-- named content -->
    <xsl:template match="named-content">
    	<xsl:choose>
    		<xsl:when test="@content-type='taxon-name'">
    		
    			<!-- <xsl:if test="@xlink:href"> -->
					<span style="text-decoration: underline;">
						<xsl:value-of select="@xlink:href" />
					</span>
    			<!-- </xsl:if> -->
    			
				<span> <!--  style="background-color:orange;"> -->
					<xsl:apply-templates />
				</span>
			</xsl:when>
			
    		<xsl:when test="@content-type='taxon-authority'">
				<span style="text-decoration: underline;">
					<xsl:apply-templates />
				</span>
			</xsl:when>

    		<xsl:when test="@content-type='taxon-status'">
				<span style="text-decoration: underline;">
					<xsl:apply-templates />
				</span>
			</xsl:when>

    		<xsl:when test="@content-type='dwc:verbatimCoordinates'">
				<span style="text-decoration: underline;">
					<xsl:apply-templates />
				</span>
			</xsl:when>
			
    		<xsl:when test="@content-type='comment'">
				<span style="text-decoration: underline;">
					<xsl:apply-templates />
				</span>
			</xsl:when>
			
			
			<xsl:otherwise>    		
				<span style="text-decoration: underline;"> 
					<xsl:apply-templates />
				</span>
			</xsl:otherwise>
		</xsl:choose>
    </xsl:template>

	<!-- label -->
    <xsl:template match="label"><b><xsl:apply-templates /></b></xsl:template>

	<!-- title -->
    <xsl:template match="title"><b><xsl:apply-templates /></b></xsl:template>

	<!-- table -->
	
	
	
    <xsl:template match="table"><table style="border-bottom:1px solid black;border-top:1px solid black;"><xsl:apply-templates /></table></xsl:template>
    <xsl:template match="tbody"><tbody style="font-size:0.8em;"><xsl:apply-templates /></tbody></xsl:template>
    <xsl:template match="th"><th><xsl:apply-templates /></th></xsl:template>
    <xsl:template match="tr"><tr><xsl:apply-templates /></tr></xsl:template>
    <xsl:template match="td">
    	<td>
    		<xsl:if test="@rowspan">
    			<xsl:attribute name="rowspan">
    				<xsl:value-of select="@rowspan" />
    			</xsl:attribute>
    		</xsl:if>
    		<xsl:if test="@colspan">
    			<xsl:attribute name="colspan">
    				<xsl:value-of select="@colspan" />
    			</xsl:attribute>
    		</xsl:if>
    		<xsl:apply-templates />
    	</td>
    </xsl:template>
    
    <!-- table wrapper -->
     <xsl:template match="table-wrap">
     	<div style="padding:10px;border:1px solid rgb(234,195,175);background-color:rgb(255,252,240);margin:4px;border-radius:4px;">
		<a>
			<xsl:attribute name="name">
				<xsl:value-of select="@id" />
			</xsl:attribute>
		</a>
     		<xsl:apply-templates />
     	</div>
     </xsl:template>

    <!-- figure -->
    <xsl:template match="fig">
    <div style="border:1px solid rgb(234,195,175);background-color:rgb(255,252,240);padding:4px;margin:4px;border-radius:4px;">
    	<div style="background-color:white;padding:10px;width:320px;text-align:center;">
    	
    	
    	
		<a>
			<xsl:attribute name="name">
				<xsl:value-of select="@id" />
			</xsl:attribute>
		</a>
    	
					<img>
						<xsl:attribute name="src">
						
							<xsl:choose>
                        		<xsl:when test='graphic/uri[@content-type="original_file"]'>
                        			<xsl:text>http://exeg5le.cloudimg.io/s/width/320/</xsl:text>
                            		<xsl:value-of select='graphic/uri[@content-type="original_file"]' />
                       			</xsl:when>
                       			<xsl:otherwise>
                       				 <xsl:value-of select="graphic/@xlink:href" />
                       			</xsl:otherwise>
                       		</xsl:choose>
				
							 
							
						</xsl:attribute>
						<xsl:attribute name="width">
							<xsl:text>300</xsl:text>
						</xsl:attribute>
					</img> 
					
		
					   	
    	</div>
    	<div style="clear:both;" />
    	<div style="padding:10px;margin-top:10px;color:rgb(101,101,101);">
    		<xsl:apply-templates />
    	</div>
    	</div>
    </xsl:template>
    

<xsl:template match="object-id">
	<!-- <xsl:value-of select="."/> -->
</xsl:template>

<!-- supress display of graphic/uri -->
<xsl:template match="graphic/uri">
</xsl:template>

<!-- references -->
<xsl:template match="ref-list">
	<ol>
		<xsl:apply-templates select="ref"/>
	</ol>
</xsl:template>

<!-- Reference list -->
<xsl:template match="ref">
	<li style="padding:4px;">
		<a>
			<xsl:attribute name="name">
				<xsl:value-of select="@id" />
			</xsl:attribute>
		</a>
		
		<xsl:apply-templates select="mixed-citation"/>
		
		<!-- Hindawi -->
		<xsl:apply-templates select="nlm-citation"/>            
		
		<!-- Biodiversity Data Journal -->
		<xsl:apply-templates select="element-citation"/>
	</li>
</xsl:template>

<!-- authors -->
<xsl:template match="//person-group">
	<xsl:apply-templates select="name"/>
	<xsl:apply-templates select="string-name"/>
</xsl:template>

<xsl:template match="name | string-name">
	<xsl:if test="position() != 1"><xsl:text>, </xsl:text></xsl:if>
	<xsl:value-of select="surname" />
	<xsl:text>, </xsl:text>
	<xsl:value-of select="given-names" />
</xsl:template>

<!-- a citation -->
<xsl:template match="mixed-citation | element-citation | nlm-citation">
	<xsl:choose>
		<xsl:when test="person-group">
			<xsl:apply-templates select="person-group"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:apply-templates select="name"/>
		</xsl:otherwise>
	</xsl:choose>
	
	<xsl:text> (</xsl:text><xsl:value-of select="year" /><xsl:text>) </xsl:text>

	<xsl:choose>
		<xsl:when test="article-title and source and volume">
			<xsl:value-of select="article-title" />
			<xsl:text>. </xsl:text>							
			<xsl:value-of select="source" />
			<xsl:text> </xsl:text>
			<xsl:value-of select="volume" />
			<xsl:text>:</xsl:text>
			<xsl:value-of select="fpage" />
			<xsl:text>-</xsl:text>
			<xsl:value-of select="lpage" />
		</xsl:when>
		<xsl:otherwise>
			<xsl:apply-templates />
		</xsl:otherwise>
	</xsl:choose>
	
	<!-- links -->
	<xsl:for-each select="uri">
		<xsl:choose>
			<xsl:when test="@xlink:type='simple'">
				<span style="background-color:blue;color:white;">
					<xsl:value-of select="." />
				</span>
			</xsl:when>
			<xsl:otherwise>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:for-each>

	<!-- identifiers -->
	<xsl:for-each select="ext-link">
		<xsl:choose>
			<xsl:when test="@ext-link-type='uri'">
				<span style="background-color:blue;color:white;">
					<xsl:value-of select="." />
				</span>
			</xsl:when>
			<xsl:when test="@ext-link-type='doi'">
				<span style="background-color:blue;color:white;">
					<xsl:text> DOI:</xsl:text>
					<xsl:value-of select="." />
				</span>
			</xsl:when>
			
			<xsl:otherwise>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:for-each>

	<xsl:for-each select="pub-id">
		<xsl:choose>
			<xsl:when test="@pub-id-type='pmid'">
				<span style="background-color:blue;color:white;">
					<xsl:text> PMID:</xsl:text>
					<xsl:value-of select="." />
				</span>
			</xsl:when>
			<xsl:when test="@pub-id-type='doi'">
				<span style="background-color:blue;color:white;">
					<xsl:text> DOI:</xsl:text>
					<xsl:value-of select="." />
				</span>
			</xsl:when>
			
			<xsl:otherwise>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:for-each>
			
</xsl:template>

    <!-- scanned page -->
    <!-- thumbnails -->
<!--    <xsl:template match="//supplementary-material/graphic[@xlink:role='thumbnail']"> -->
    <xsl:template match="//supplementary-material/graphic">
        <div style="float:left;padding:20px;">
            <!-- link to HTML -->
            <a>
                <xsl:attribute name="href">
                    <xsl:text>work/</xsl:text>
                    <xsl:value-of select="$work" />
                    <xsl:text>/full</xsl:text>
                    <xsl:text>#page=</xsl:text>
                    <xsl:value-of select="@xlink:title" />
                </xsl:attribute>
                <img style="border:1px solid rgb(192,192,192);" height="140">
                    <xsl:attribute name="src">
                        <!--
					<xsl:text>Med_Hist_1985_Jan_29(1)_1-32/</xsl:text><xsl:value-of select="substring-before(@xlink:href, '.tif')" /><xsl:text>.gif</xsl:text>
				-->
                        <xsl:value-of select="@xlink:href" />
                    </xsl:attribute>
                </img>
            </a>
            <!-- Page name -->
            <div style="text-align:center;width:100px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                <xsl:value-of select="@xlink:title" />
            </div>
        </div>
    </xsl:template>



</xsl:stylesheet>