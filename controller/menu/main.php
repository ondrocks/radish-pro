<?php

	echo "<img src='media/radish.gif' class='radish'/>";

if($user->isValid())
	echo "<p class='welcome'>Welcome " . $user->getName() . "</p>";
?>
<div id='menuBar'>
<?php
	leftMenu();
	if(controllerFront::getController() == 'config')
		echo "</div>";
	else if(controllerFront::getController() == 'events')
		rightMenu2();
	else
		rightMenu1();

function leftMenu()
{
?>
        <div class='floatleft'>
        	<a class='bolditalic' href='javascript:toggleMode()'>list/itemview</a> | 
		<a class='bolditalic' href='javascript:listEmail()'>email</a> | 
		<a class='bolditalic' href='/events/'>events</a> | 
		<a class='bolditalic' href='/people/'>people</a> | 
		<a href='/companies/' class='bolditalic'>companies</a> | 
		<a class='bolditalic' href='/config/'>settings</a>
	</div>
<?php
}
function rightMenu1()
{
?>
        <div class='floatright'>
		<span class='version'><?php echo PUtil::getVersion();?></span>
                <form class='inlineForm' action='/search/' id='form01' method='POST'>
                        <input type='radio' name='select' value='linkedin' checked/> LinkedIn
                        <input type='radio' name='select' value='linkediniscompany' /> company
                        <input type='radio' name='select' value='local'/> local
                        <input class='inlineInput' name='country' size='2'/> country
                        <input type='hidden' name='q' id='q'/>
                        <input class='inlineInput' name='qq' type='text'/>
                        <input class='inlineInput' type='image' src='controller/events/img/search.png'/>
                </form>
        </div>
</div>
<?php
}
function rightMenu2()
{
?>
	<div class='floatright'>
		<span class='version'><?php echo PUtil::getVersion();?></span>
		<form class='inlineForm' action='/search/' id='form01' method='POST'>
			<input type='radio' value='google' name='select'/> Google
			<input type='text' class='inputCountry' name='country' value='nl'/>
			<input type='radio' value='twitter' name='select'/>Twitter
			<input type='radio' name='select' value='facebook' checked=checked/>FB-nl
			<input class='inlineInput' id='q' name='q' type='text'/>
			<input class='inlineInput' type='image' src='controller/events/img/search.png'/>
		</form>
	</div>
</div>
<?php
}
?>
