<footer>
    <div class='footer-copyright text-center'>©MK 2021</div>
</footer>
</div>
</div>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- redirect modal -->
<div class="modal fade" id="redirectModal" tabindex="-1" role="dialog" aria-labelledby="redirectModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-m" id="exampleModalLabel">Poruka</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-danger" id="redirect-msg">
        <?php if(isset($_SESSION['redirect_message'])) { echo $_SESSION['redirect_message']; } ?>
        </div>
        <div class="modal-footer text-m">
          <a class="btn btn-x" data-dismiss="modal" aria-label="Close" role="button">U redu</a>
        </div>
      </div>
    </div>
  </div>

<script>
    $('.modal').on('hidden.bs.modal', function() {
        $('.val-msg').empty();
    });
</script>
<script src="js/user_modals.js"></script>
<?php
if(isset($user_id) && isset($user_name)) {
    cms_user_mobile($menu_items, $menu_links, $category,$user_id,$user_name,$avi);
}
?>
</body>

</html>