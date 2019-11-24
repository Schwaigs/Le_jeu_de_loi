/******************************************************************************
 * SCRIPT BANDEAU DEROULANT LOIS
 ******************************************************************************/

 /* Open when someone clicks on the span element */
 function ouvreBandeauLoi() {
   //Si l'arbre est ouvert, on le referme
   if (document.getElementById("arbreDepl").style.width = "66%") {
     document.getElementById("arbreDepl").style.width = "0%";
   }

   if (document.getElementById("bandeauLoiDepl").style.height == "0%") {
     document.getElementById("bandeauLoiDepl").style.height = "1500%";
   }
   else {
     document.getElementById("bandeauLoiDepl").style.height = "0%";
   }
 }
