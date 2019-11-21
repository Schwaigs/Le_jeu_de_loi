/******************************************************************************
 * SCRIPT INDEX
 ******************************************************************************/

 /* Open when someone clicks on the span element */
 function ouvreArbre() {
   //Si le bandeau de loi est ouvert on le ferme
   if (document.getElementById("bandeauLoiDepl").style.height == "1500%") {
     document.getElementById("bandeauLoiDepl").style.height = "0%";
   }
   
   document.getElementById("arbreDepl").style.width = "66%";
 }

 /* Close when someone clicks on the "x" symbol inside the overlay */
 function fermeArbre() {
   document.getElementById("arbreDepl").style.width = "0%";
 }
