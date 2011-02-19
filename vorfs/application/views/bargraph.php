<?php $this->load->helper('url'); ?>

<p><object width="571" height="246"
data="data:application/x-silverlight-2," type="application/x-silverlight-2">
<param name="source" value="<?php echo base_url();?>bargraph.xap"/>
<param name="onError" value="onSilverlightError" />
<param name="background" value="white" />
<param name="minRuntimeVersion" value="4.0.50826.0" />
<param name="autoUpgrade" value="true" />
<param name="initParams" value="<?php //the stuff that goes into the bar graph is HERE:
echo $barinput;

?>" />
<param name="background" value="#00FFFFFF" /> <param name="windowless" value="true" />
<param name="pluginbackground" value="#00FFFFFF" />
<a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=4.0.50826.0" style="text-decoration:none">
<img src="http://go.microsoft.com/fwlink/?LinkId=161376" alt="Get Microsoft Silverlight" style="border-style:none"/>
</a>
</object></p>