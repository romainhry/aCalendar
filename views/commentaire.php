<?if($niveau>0){
  ?>
  <div class="comm" style="margin-left: <? echo $niveau*30; ?>px; display: none; padding: 1rem;
    margin-bottom: 2rem;border: dotted gray;">
    <?
}else {
  ?>
    <div class="comm" style="margin-left: <? echo $niveau*30 ;?>px;padding: 1rem;
      margin-bottom: 2rem;border: dotted gray;">
  <?
} ?>

  <div style="display:flex;">
    <a><? echo $commentateur->pseudo();?></a><p style="margin: 0px;"> : <? echo ' '.$comm->commentaire() ?></p>
  </div>
  <p>Posté le : <? echo $comm->dateComm() ?></p>
  </br>
  <div class="aime">
  <?
    for($i=0;$i<count($UtilAime);$i++)
    {
      ?>
      <a><? echo $UtilAime[$i]->pseudo();?></a><p>, </p>
      <?
    }
    if(count($UtilAime)>1)
    {
      ?>
      <p>aiment</p>
      <?
    }
    else if(count($UtilAime)==1)
    {
      ?>
      <p>aime</p>
      <?
    }
    ?>
  </div>
  <div style="display: flex;">
    <a class="ecrire">Répondre</a>
    <a class="voirReponses" style='margin-left: 1rem'>    Voir réponses</a>
    <a style='margin-left: 1rem'>J'aime</a>
  </div>

  <form class ="repondre" style ="display: none;" method="post" action="<?=BASEURL?>/index.php/activite/doCommentaire/<? echo $activite->idActivite();?>/<? echo $comm->idComm();?>">
      <textarea name="contenu" style="width: 100%" id="contenu" class="question" rows = "2" cols = "20" placeholder = "Commentez moi" ></textarea>
    <div class="formline">
      <input type="submit" value="Validate">
    </div>
  </form>
  <script type="text/javascript">

    var hiddenBox = $( ".repondre" );
    $( ".ecrire" ).on( "click", function( event ) {
      hiddenBox.show("slow");
    });
  </script>

  <script type="text/javascript">

    var hiddenBox2 = $( ".comm" );
    $( ".voirReponses" ).on( "click", function( event ) {
      hiddenBox2.show("slow");
    });
  </script>
</div>
