/** ------------------------------------------------------------------
 * JavaScripts which are loaded by the OnLoad function of the body tag
 * -------------------------------------------------------------------
 */

// executes JavaScripts for the loginbox and the menu
function InitializePage() {
  LoadLoginBox();
  LoadMenu();
}

// functions for mouse-over and click events of non-content-buttons
function MouseOverBigButton(source)
{
  source.firstChild.style.visibility = "visible";
}
function MouseOutBigButton(source)
{
  source.firstChild.style.visibility = "hidden";
}

/** ---------------------
 * Loginbox functionality
 * ----------------------
 */

// initialisation of the loginbox status by the value of the variable 'loginStatus' which is provided to the HTML-document by PHP in the file 'header.inc'
function LoadLoginBox()
{
  if(loginStatus == "false") {
    document.getElementById('LoginstatusText_1').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-you-are-not-logged-in.gif')";
    document.getElementById('ButtonText').style.backgroundImage = "url('" + IMAGES + "/buttons/_sbutton_login.gif')";
    document.getElementById('LoginstatusText_2').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-create-account.gif')";
    document.getElementById('LoginstatusText_2_1').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-create-account.gif')";
    document.getElementById('LoginstatusText_2_2').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-create-account-over.gif')";
  } else {
    document.getElementById('LoginstatusText_1').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-welcome.gif')";
    document.getElementById('ButtonText').style.backgroundImage = "url('" + IMAGES + "/buttons/_sbutton_myaccount.gif')";
    document.getElementById('LoginstatusText_2').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-logout.gif')";
    document.getElementById('LoginstatusText_2_1').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-logout.gif')";
    document.getElementById('LoginstatusText_2_2').style.backgroundImage = "url('" + IMAGES + "/loginbox/loginbox-font-logout-over.gif')";
  }
}

// mouse-over and click events of the loginbox
function MouseOverLoginBoxText(source)
{
  source.lastChild.style.visibility = "visible";
  source.firstChild.style.visibility = "hidden";
}
function MouseOutLoginBoxText(source)
{
  source.firstChild.style.visibility = "visible";
  source.lastChild.style.visibility = "hidden";
}
function LoginButtonAction()
{
  if(loginStatus == "false") {
    window.location = LINK_ACCOUNT + "?subtopic=accountmanagement";
  } else {
    window.location = LINK_ACCOUNT + "?subtopic=accountmanagement";
  }
}
function LoginstatusTextAction(source) {
  if(loginStatus == "false") {
    window.location = LINK_ACCOUNT + "?subtopic=createaccount";
  } else {
    window.location = LINK_ACCOUNT + "?subtopic=accountmanagement&action=logout";
  }
}

/** ------------------
 *  Menu functionality
 *  ------------------
 */

var menu = new Array();
menu[0] = new Object();
var unloadhelper = false;

// load the menu and set the active submenu item by using the variable 'activeSubmenuItem' (provided to HTML-document by PHP in the file 'header.inc'
function LoadMenu()
{
  document.getElementById("submenu_"+activeSubmenuItem).style.color = "white";
  document.getElementById("ActiveSubmenuItemIcon_"+activeSubmenuItem).style.visibility = "visible";
  if(self.name.lastIndexOf("&") == -1) {
    self.name = "news=1&account=0&community=0&library=0&forum=0&shops=0&";
  }
  FillMenuArray();
  InitializeMenu();
}
function SaveMenu()
{
  if(unloadhelper == false) {
    SaveMenuArray();
    unloadhelper = true;
  }
}

// store the values of the variable 'self.name' in the array menu
function FillMenuArray()
{
  while(self.name.length > 0 ){
    var mark1 = self.name.indexOf("=");
    var mark2 = self.name.indexOf("&");
    var menuItemName = self.name.substr(0, mark1);
    menu[0][menuItemName] = self.name.substring(mark1 + 1, mark2);
    self.name = self.name.substr(mark2 + 1, self.name.length);
  }
}

// hide or show the corresponding submenus
function InitializeMenu()
{
  for(menuItemName in menu[0]) {
    if(menu[0][menuItemName] == "0") {
      document.getElementById(menuItemName+"_Submenu").style.visibility = "hidden";
      document.getElementById(menuItemName+"_Submenu").style.display = "none";
      document.getElementById(menuItemName+"_Lights").style.visibility = "visible";
      document.getElementById(menuItemName+"_Extend").style.backgroundImage = "url(" + IMAGES + "/general/plus.gif)";
    }
    else {
      document.getElementById(menuItemName+"_Submenu").style.visibility = "visible";
      document.getElementById(menuItemName+"_Submenu").style.display = "block";
      document.getElementById(menuItemName+"_Lights").style.visibility = "hidden";
      document.getElementById(menuItemName+"_Extend").style.backgroundImage = "url(" + IMAGES + "/general/minus.gif)";
    }
  }
}

// reconstruct the variable "self.name" out of the array menu
function SaveMenuArray()
{
  var stringSlices = "";
  var temp = "";
  for(menuItemName in menu[0]) {
    stringSlices = menuItemName + "=" + menu[0][menuItemName] + "&";
    temp = temp + stringSlices;
  }
  self.name = temp;
}

// onClick open or close submenus
function MenuItemAction(sourceId)
{
  if(menu[0][sourceId] == 1) {
    CloseMenuItem(sourceId);
  }
  else {
    OpenMenuItem(sourceId);
  }
}
function OpenMenuItem(sourceId)
{
  menu[0][sourceId] = 1;
  document.getElementById(sourceId+"_Submenu").style.visibility = "visible";
  document.getElementById(sourceId+"_Submenu").style.display = "block";
  document.getElementById(sourceId+"_Lights").style.visibility = "hidden";
  document.getElementById(sourceId+"_Extend").style.backgroundImage = "url(" + IMAGES + "/general/minus.gif)";
}
function CloseMenuItem(sourceId)
{
  menu[0][sourceId] = 0;
  document.getElementById(sourceId+"_Submenu").style.visibility = "hidden";
  document.getElementById(sourceId+"_Submenu").style.display = "none";
  document.getElementById(sourceId+"_Lights").style.visibility = "visible";
  document.getElementById(sourceId+"_Extend").style.backgroundImage = "url(" + IMAGES + "/general/plus.gif)";
}

// mouse-over effects of menubuttons and submenuitems
function MouseOverMenuItem(source)
{
  source.firstChild.style.visibility = "visible";
}
function MouseOutMenuItem(source)
{
  source.firstChild.style.visibility = "hidden";
}
function MouseOverSubmenuItem(source)
{
  source.style.backgroundColor = "#14433F";
}
function MouseOutSubmenuItem(source)
{
  source.style.backgroundColor = "#0D2E2B";
}


/** -------------------------
 * functions related to forms 
 * --------------------------
 */

// set cursor focus in form (g_FormName) to field (g_FieldName)
function SetFormFocus()
{
  if (g_FormName.length > 0 && g_FieldName.length > 0 ) {
    document.forms[g_FormName].elements[g_FieldName].focus();
  }
}


// toggle masked texts with readable texts
function ToggleMaskedText(a_TextFieldID)
{
  m_DisplayedText = document.getElementById('Display' + a_TextFieldID).innerHTML;
  m_MaskedText = document.getElementById('Masked' + a_TextFieldID).innerHTML;
  m_ReadableText = document.getElementById('Readable' + a_TextFieldID).innerHTML;
  if (m_DisplayedText == m_MaskedText) {
    document.getElementById('Display' + a_TextFieldID).innerHTML = document.getElementById('Readable' + a_TextFieldID).innerHTML;
    document.getElementById('Button' + a_TextFieldID).src = IMAGES + '/general/hide.gif';
  } else {
    document.getElementById('Display' + a_TextFieldID).innerHTML = document.getElementById('Masked' + a_TextFieldID).innerHTML;
    document.getElementById('Button' + a_TextFieldID).src = IMAGES + '/general/show.gif';
  }
}