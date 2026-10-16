<?php include("../includes/configuration.php");
define ("OUTPUT_FILE", "../sitemap.xml");

$weburl = SITEURL;
$getstaticpagedetails=selectQuery(STATIC_PAGE,"cancellation_refund_data,privacy_policy_data,terms_condition_data,faq_data,about_us_data,international_ship_data","id= 1");

$urlstr = '<?xml version="1.0" encoding="UTF-8"?>';
$urlstr= $urlstr.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $urlstr = $urlstr."
<url><loc>$weburl/</loc><changefreq>daily</changefreq><priority>0.5</priority></url>
<url><loc>$weburl/login</loc><changefreq>daily</changefreq><priority>0.5</priority></url>
<url><loc>$weburl/register</loc><changefreq>daily</changefreq><priority>0.5</priority></url>
<url><loc>$weburl/contact</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";
// static page link

if($getstaticpagedetails[0]['cancellation_refund_data']== 1)  {   $urlstr = $urlstr."<url><loc>$weburl/cancellation-return</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if($getstaticpagedetails[0]['privacy_policy_data']== 1)  { $urlstr = $urlstr."<url><loc>$weburl/privacy-policy</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";}
if($getstaticpagedetails[0]['terms_condition_data']== 1)  { $urlstr = $urlstr."<url><loc>$weburl/terms-condition</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if($getstaticpagedetails[0]['faq_data']== 1)  { $urlstr = $urlstr."<url><loc>$weburl/faq</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; } 
if($getstaticpagedetails[0]['about_us_data'] == 1)  { $urlstr = $urlstr."<url><loc>$weburl/about-us</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if($getstaticpagedetails[0]['international_ship_data'] == 1)  { $urlstr = $urlstr."<url><loc>$weburl/international-shipping</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }

//  offer link 
$offcnts = selectQuery( OFFER, "count(offer_id) as offercount", "isActive='1'  " );
if($offcnts[0]['offercount']) { $urlstr = $urlstr."<url><loc>$weburl/offer</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";}

//blog link
$getblogc=selectQuery(BLOG,"count(id) as blogcount","isActive = '1'");
if($getblogc[0]['blogcount']) { 
       $blogcat=selectQuery(BLOGCAT,"url_title,category_name,cat_id","isActive='1' order by category_name ASC");
       for($i=0;$i<count($blogcat);$i++){
        $urlstr = $urlstr."<url><loc>$weburl/bloglist/".$blogcat[$i]['url_title']."/</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; 
      }

    }
$getblog=selectQuery(BLOG,"url_title","isActive='1' " );
for($i=0;$i<count($getblog);$i++){
 
  $urlstr = $urlstr."<url><loc>$weburl/blog/".$getblog[$i]['url_title']."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";
} 

	
//socail media link
if(FBLINK != "") { $urlstr = $urlstr."<url><loc>".FBLINK."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if(LINKEDIN != "") { $urlstr = $urlstr."<url><loc>".LINKEDIN."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if(PINTEREST != "") { $urlstr = $urlstr."<url><loc>".PINTEREST."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if(INSTAGRAMLINK != "") { $urlstr = $urlstr."<url><loc>".INSTAGRAMLINK."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";}
if(YOUTUBELINK != "") { $urlstr = $urlstr."<url><loc>".YOUTUBELINK."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";}
if(TWITTERLINK != "") { $urlstr = $urlstr."<url><loc>".TWITTERLINK."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }
if(PLAYSTORELINK != "") { $urlstr = $urlstr."<url><loc>".PLAYSTORELINK."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; }


//allcategory url
$parent = selectQuery(PRODCAT,"cat_name,id,type","isActive='1' order by priority");
for($i=0;$i<count($parent);$i++){
	$urlstr = $urlstr."<url><loc>$weburl/".getUrl($parent[$i]['type'],$parent[$i]['id'])."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; 
}

// all product url 
$all_product = selectQuery(PRODINFO,"id","isActive='1' and  	isApproved  = '1'  and url_title <> ''");

for($i=0;$i<count($all_product);$i++){
	$urlstr = $urlstr."<url><loc>$weburl/".getUrl('Product',$all_product[$i]['id'])."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>"; 
}
$urlstr = $urlstr."</urlset>";  
//echo $urlstr;

  $pf = fopen(OUTPUT_FILE,"w");
    if (!$pf)
    {
        echo "ERROR: Cannot create " . OUTPUT_FILE . "!" . NL;
        return;
    }
    else
    {
         fwrite($pf, $urlstr);
    }
submitSitemap(SITEURL);
    function submitSitemap($site)
{ $url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.htmlentities($site.'/sitemap.xml');
  $response = file_get_contents($url);
  if($response){ echo $response; }
  else{ echo "Failed to submit sitemap";}
}
?>
    