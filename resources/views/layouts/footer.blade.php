<footer class="index-footer">
    <div class="text-center padder clearfix">
        <p>
            <small>{{Config::get('client.name').' '.Config::get('client.copyright')}}</small><br><br>
            <a class="btn btn-xs btn-circle btn-twitter" target="_blank" href="{{ Config::get('client.twitter') }}" ><i class="fa fa-twitter"></i></a>
            <a class="btn btn-xs btn-circle btn-facebook" target="_blank" href=<?php echo Config::get('client.facebook')?>><i class="fa fa-facebook"></i></a>
            <a class="btn btn-xs btn-circle btn-gplus" target="_blank" href=<?php echo Config::get('client.google');?>><i class="fa fa-google-plus"></i></a>
        <div></div>
        </p>


    </div>
