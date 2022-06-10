</div>
<div id="footer">
    <p id='copy'>&copy; Diploma Shop 2022<p>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $('.glow-button').click(function(){
            var id = $(this).attr("data-id");
            $.post("/cart/addAjax/"+id, {}, function(data){
                $('.cart_count').html(data);
            });
            return false;
        });
    });

</script>
</body>
</html>
