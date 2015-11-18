@extends('welcome')

@section('content')

    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="/images/travel.jpg">
          <div class="container">
            <div class="carousel-caption">
              <h1>Traveling with friends?</h1>
              <p>Divide all expenses with your fellow travelers for one final settlement.</p>
              <p><a class="btn btn-lg btn-primary" href="/register" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="/images/groceries.jpg">
          <div class="container">
            <div class="carousel-caption">
              <h1>Have a roommate?</h1>
              <p>Keep a record of all expenses for the apartment, including that basket of fruit.</p>
              <p><a class="btn btn-lg btn-primary" href="/register" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="/images/party.jpg">
          <div class="container">
            <div class="carousel-caption">
              <h1>Planning a large party?</h1>
              <p>Get a handle on how much it will cost each one of you.</p>
              <p><a class="btn btn-lg btn-primary" href="/register" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-4">
          <img class="img-circle" src="/images/expenses.jpg" alt="Generic placeholder image" width="140" height="140">
          <h2>Share Expenses</h2>
          <p>Create Reports and add friends to it. Anyone on the Report
          	will be able to add and modify their Expenses.</p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" src="/images/notification.jpg" alt="Generic placeholder image" width="140" height="140">
          <h2>Automatic Notifications</h2>
          <p>Email notifications sent out when Reports are created or closed, Settlements determined and completed.</p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" src="/images/settle.jpg" alt="Generic placeholder image" width="140" height="140">
          <h2>Settle Once</h2>
          <p>Consolidate Settlements across Reports to settle once. Reduce number of actual cash transactions.</p>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->


      <!-- START THE FEATURETTES -->

      <hr>

      <div class="row featurette">
        <div class="col-md-12">
          <span class="lead">About App-portion!</span>
          <p class="text-muted ">App-portion is a free tool through which you can split (apportion) expenses with a group of people. Create
          	a report and add people to it. Anyone on the report will be able to add and modify their expenses. Once all expenses have
          	been entered, close the report and determine settlements for the report. Automatic email notifications are sent at every
          	step of this process. Settle per individual report or across reports. Future versions planned include integration with 
          	payment providers such as Venmo.
          </p>
        </div>
      </div>

      <hr>

      <!-- /END THE FEATURETTES -->

@endsection
@section('footer')
	<section class="bottom_last_ftoor clearfix">
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<p>© Copyright 2015. All rights reserved | 
				<a href="/privacy.html">Privacy Policy</a> | 
				<a href="/terms.html">Terms &amp; Condition</a> | 
				<a href="mailto:info@app-portion.com">Contact Us</a>
			</p>
		</div>
	</section>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-2.1.4.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
@stop