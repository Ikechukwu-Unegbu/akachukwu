<div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-skeleton">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                                    <div class="sub-card pulsate">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card-content">
                                <div class="block2 pulsate">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <div class="sub-card pulsate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
</div>

@push('styles')
<style>
    body {
        overflow-x: hidden
    }

    .dashboard-skeleton {
        margin-top: 15px
    }

    .dashboard-skeleton .sub-card {
        box-sizing: border-box;
        background: #e1e1e1;
        position: relative;
        margin-bottom: 10px;
        border-radius: 10px!important;
        height: 160px
    }

    .dashboard-skeleton .pulsate {
        background: linear-gradient(90deg,#ddd,#f0f0f0,#ddd,#f0f0f0);
        background-size: 400% 400%;
        -webkit-animation: Gradient 2s ease infinite;
        animation: Gradient 2s ease infinite
    }

    .dashboard-skeleton .card-content {
        clear: both;
        box-sizing: border-box
    }

    .dashboard-skeleton .block2 {
        box-sizing: border-box;
        background: #e1e1e1;
        height: 300px;
        border-radius: 10px!important
    }

    @-webkit-keyframes Gradient {
        0% {
            background-position: 0 50%
        }

        50% {
            background-position: 100% 50%
        }

        to {
            background-position: 0 50%
        }
    }

    @keyframes Gradient {
        0% {
            background-position: 0 50%
        }

        50% {
            background-position: 100% 50%
        }

        to {
            background-position: 0 50%
        }
    }
    </style>
@endpush
