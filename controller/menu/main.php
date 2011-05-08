<?php
if(controllerFront::getController() == 'people')
{
	echo "<img src='/radish.gif' class='radish'/><p class='welcome'>Welcome " . $user->getName() . "</p>";
?>
<div id='menuBar'>
        <div class='floatleft'>
        	<a class='bolditalic' href=''>list/itemview</a> | 
		<a class='bolditalic' href='javascript:listEmail()'>email</a> | 
		<a class='bolditalic' href='/events/'>events</a> | 
		<a href='/companies/' class='bolditalic'>companies</a> | 
		<a class='bolditalic' href='/config/'>settings</a>
	</div>
        <div class='floatright'>
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
else if(controllerFront::getController() == 'companies')
{
?>
<div id='menuBar'>
        <div class='floatleft'>
                <a class='bolditalic' href=''>list/itemview</a> | 
                <a class='bolditalic' href='javascript:listEmail()'>email</a> | 
                <a class='bolditalic' href='/events/'>events</a> | 
		<a class='bolditalic' href='/people/'>people</a> |
                <a class='bolditalic' href='/config/'>settings</a>
        </div>
        <div class='floatright'>
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
else if(controllerFront::getController() == 'events')
{
?>
<div id='statusBar'></div>

<div id='menuBar'>
        <div class='floatleft'>
                <a href='' class='bolditalic'>list/itemview</a> |
                <a href='' class='bolditalic'>chat (0)</a> |
                <a href='/people/' class='bolditalic'>people</a> |
                <a href='/events/?step=step0' class='bolditalic'>profiles</a> |
                <a href='?' class='bolditalic'>reports</a> |
                <a href='/config/' class='bolditalic'>settings</a> |
                <a href='' class='bolditalic'>logout</a>
        </div>
        <div class='floatright'>
                <span class='version'>v0.0.10</span>
                <form class='inlineForm' action='/search/' id='form01' method='POST'>
                        <input type='radio' value='google' name='select'/> Google
                        <input type='text' class='inputCountry'  name='country' value='nl'/>
                        <input type='radio' value='twitter' name='select'/>Twitter
                        <input type='radio' name='select' value='facebook' checked=checked/>FB-nl
                        <input class='inlineInput' id='q' name='q' type='text'/>
                        <input class='inlineInput' type='image' src='controller/events/img/search.png'/>
                </form>
        </div>
</div>


<?php
}
else if(controllerFront::getController()== 'config')
{
?>
<div id='menuBar'>
        <a href='/events/' class='bolditalic'>home</a> | <a href='/people/' class='bolditalic'>people</a>
</div>
<?php
}
?>
