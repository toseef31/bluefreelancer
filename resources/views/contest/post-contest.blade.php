@extends('layouts.app')
@section('content')
    <!-- Page Content -->
    <div class="bg-secondary py-4">
        <div class="container pt-2 pb-3">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <a href="/project-listing" class="btn btn-block bg-gray-800 text-white w-md-auto mt-2 mr-md-2">Browse
                    Projects</a>
                <a href="/contest-listing" class="btn btn-block bg-gray-800 text-white w-md-auto mr-md-2">Browse
                    Contests</a>
                <a href="/browse/category" class="btn btn-block bg-gray-800 text-white w-md-auto mr-md-2">Browse
                    Categories</a>
                <a href="/showcases" class="btn btn-block bg-gray-800 text-white w-md-auto mr-md-2">Showcase</a>
                <a href="/post-contest" class="btn btn-block btn-primary w-md-auto ml-auto">Start a Contest</a>
            </div>
        </div>
    </div>

    <!-- Title -->
    <div class="bg-secondary text-center bg-cover py-5"
        style="background-image: url({{ url('assets/img/dashboard/banner-1.jpg') }});">
        <h1 class="h5 font-weight-bold text-white">Post Contest</h1>
    </div>

    <section class="container py-5">
        <h2 class="h4 font-weight-bold mb-4">
            <i class="fa fa-desktop mr-2"></i>
            Start free Contest registration
        </h2>
        <p class="font-size-sm font-weight-bold mb-4 pl-4 ml-3 pb-1">
            <i class="fa fa-warning text-danger mr-2"></i> Below <span
                class="icon icon-sm bg-secondary text-white mx-1">1</span> from <span
                class="icon icon-sm bg-secondary text-white mx-1">5</span> are the required items, and you must fill out the
            order in the order listed below to be free to register for the project!
        </p>

        <div class="row">
            <div class="col-md-9">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('contest.store') }}" method="post" id="post_contest_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card card-bordered rounded-xl shadow p-md-4 mb-4">
                        <div class="card-body px-5">
                            <div class="form-group mb-4">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">1</div>
                                    Please select a contest type
                                </div>
                                <select class="form-control" data-toggle="select" id="contest_select_category"
                                    name="main_category" required>
                                    <option value="">Select Contest Category</option>
                                    {{-- Coming Option From Ajax.js (AJAX CALL) --}}
                                </select>
                                <br>
                                <select style="display: none;" class="form-control" data-toggle="select"
                                    id="contest_select_subcategory" name="sub_category" required>
                                    {{-- Coming Option From Ajax.js (AJAX CALL) --}}
                                </select>
                            </div>

                            <div class="form-group mb-4 pt-2">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">2</div>
                                    Please enter contest title
                                </div>

                                <label class="font-size-ms font-weight-bold mb-2" for="projectTitle">
                                    Project title
                                    <div class="icon icon-sm ml-2" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title="Popover title"
                                        data-content="And here's some amazing content. It's very engaging. Right?">
                                        <i class="fa fa-question"></i>
                                    </div>
                                </label>
                                <input type="text" class="form-control" id="projectTitle" placeholder="Enter Contest name"
                                    name="title">
                            </div>

                            <div class="form-group mb-4">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">3</div>
                                    Fill out the contest
                                </div>

                                <label class="font-size-ms font-weight-bold mb-2">
                                    Skills required for the contest
                                    <div class="icon icon-sm ml-2" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title="Popover title"
                                        data-content="And here's some amazing content. It's very engaging. Right?">
                                        <i class="fa fa-question"></i>
                                    </div>
                                </label>

                                <select class="custom-select" data-toggle="select" id="post_contest_skills" multiple>
                                    {{-- Coming Option From Ajax.js (AJAX CALL) --}}
                                </select>
                                <input type="hidden" name="contest_skills" id="selected_post_contest_skills" required>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-size-ms font-weight-bold mb-2" for="projectTitle">
                                    Describe your contest
                                    <div class="icon icon-sm ml-2" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title="Popover title"
                                        data-content="And here's some amazing content. It's very engaging. Right?">
                                        <i class="fa fa-question"></i>
                                    </div>
                                </label>

                                <div class="quill-editor">
                                    <div id="quill-toolbar">
                                        <span class="ql-formats">
                                            <button class="ql-bold"></button>
                                            <button class="ql-italic"></button>
                                            <button class="ql-underline"></button>
                                        </span>
                                        <span class="ql-formats">
                                            <select class="ql-color"></select>
                                            <select class="ql-background"></select>
                                        </span>
                                        <span class="ql-formats">
                                            <button class="ql-script" value="sub"></button>
                                            <button class="ql-script" value="super"></button>
                                        </span>
                                        <span class="ql-formats">
                                            <button class="ql-header" value="1"></button>
                                            <button class="ql-header" value="2"></button>
                                        </span>
                                        <span class="ql-formats">
                                            <button class="ql-list" value="ordered"></button>
                                            <button class="ql-list" value="bullet"></button>
                                            <button class="ql-indent" value="-1"></button>
                                            <button class="ql-indent" value="+1"></button>
                                        </span>
                                        <span class="ql-formats">
                                            <select class="ql-align"></select>
                                        </span>
                                        {{-- <span class="ql-formats">
                                        <button class="ql-link"></button>
                                        <button class="ql-image"></button>
                                        <button class="ql-video"></button>
                                    </span> --}}
                                        <span class="ql-formats">
                                            <button class="ql-clean"></button>
                                        </span>
                                    </div>

                                    <div id="quill-editor"></div>
                                    <textarea style="display: none;" name="contest_description"
                                        id="contest_description"></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label class="font-size-ms font-weight-bold mb-2">
                                            <i class="fa fa-warning text-danger mr-2"></i> Attach a file here that might be
                                            helpful in explaining your project in brief (1MB)
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="file" data-max-file-size="1MB">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">4</div>
                                    Contest expenditure budget
                                </div>

                                <p class="font-size-sm"><i class="fa fa-warning text-danger mr-2"></i> It is recommended
                                    to
                                    start the prize starting at 10,000 won [ten thousand won]. [Inquiry for Bluefreelancer
                                    contest prize money over 100 million won]</p>

                                <div class="card card-body px-5 rounded-xl">
                                    <div class="range-slider"
                                        data-range-options='{"start": 0, "pips": {"mode": "count", "values": 2}, "tooltips": true, "range": {"min": 0, "max": 10000}}'>
                                        <div class="range-slider-ui my-5"></div>

                                        <div class="row pt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    {{-- <div class="input-group-prepend"><span class="input-group-text">$</span>
                                                </div> --}}
                                                    <input class="form-control range-slider-value-min" name="budget"
                                                        type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="currency" data-toggle="select">
                                                    <option value="USD">US Dollar (USD $)</option>
                                                    <option value="KRW">Korea (KRW ₩)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4 pt-2">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">5</div>
                                    Contest period
                                </div>

                                <p class="font-size-sm"><i class="fa fa-warning text-danger mr-2"></i> If the winner is
                                    not
                                    selected within 7 days of the deadline for the contest, the applicant will be paid 1 /
                                    n.
                                </p>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            Work (Days)
                                        </div>
                                    </div>
                                    <input type="number" name="days" class="form-control"
                                        placeholder="The contest can be up to 30 days in duration, and one winner must be selected within seven days of the registration deadline.">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="font-size-sm font-weight-bold mb-3">
                                    <div class="icon bg-secondary text-white mr-2">6</div>
                                    Please select an Bluefreelancer service [optional]
                                </div>

                                <p class="font-size-ms">
                                    <i class="fa fa-warning text-danger mr-2"></i>
                                    Note: When using Bluefreelancer service, you will not be charged cancellation or refund
                                    for
                                    each registration fee.
                                </p>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="Chat">
                                            <label class="custom-control-label" for="Chat"></label>
                                        </div>
                                        <button class="btn btn-primary btn-sm px-4 ml-n3">Chat</button>
                                    </div>

                                    <div class="col-md-7">
                                        <h5 class="font-size-sm font-weight-bold">Using chat is an indicator of the high
                                            success
                                            of the project. Unlimited chats by registration</h5>
                                        <p class="font-size-ms">You can use unlimited chat anytime and anywhere until the
                                            project is completed. <a href="#"
                                                class="btn btn-sm btn-danger font-size-xs py-1">Caution <i
                                                    class="fa fa-caret-right ml-1"></i></a></p>
                                        <p class="font-size-ms text-muted">Free Event Event [Until June 30]</p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <del class="text-muted">$500</del>
                                            <span class="ml-1">$0</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="imageVideo">
                                            <label class="custom-control-label" for="imageVideo"></label>
                                        </div>
                                        <button class="btn btn-primary btn-sm px-4 ml-n3">Image Video</button>
                                    </div>

                                    <div class="col-md-7">
                                        <h5 class="font-size-sm font-weight-bold">Confirmed from freelancers to the end of
                                            the
                                            project safely!</h5>
                                        <p class="font-size-ms">If you use free video (video) service, you need to
                                            purchase
                                            chat
                                            service to go to separate screen and use 1: 1 live video chat. <a href="#"
                                                class="btn btn-sm btn-info font-size-xs py-1">Introduction <i
                                                    class="fa fa-caret-right ml-1"></i></a></p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <span class="ml-1">$0</span>
                                            <p>Free to purchase chat</p>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="private">
                                            <label class="custom-control-label" for="private"></label>
                                        </div>
                                        <button class="btn btn-primary btn-sm disabled px-4 ml-n3">Private</button>
                                    </div>

                                    <div class="col-md-7">
                                        <p class="font-size-ms">Private services are effective when looking for
                                            professional
                                            freelancers to develop core projects that require security. <a href="#"
                                                class="btn btn-sm btn-info font-size-xs py-1">Introduction <i
                                                    class="fa fa-caret-right ml-1"></i></a></p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <span class="ml-1">$0</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="Sealed">
                                            <label class="custom-control-label" for="Sealed"></label>
                                        </div>
                                        <button class="btn btn-success btn-sm disabled px-4 ml-n3">Sealed</button>
                                    </div>

                                    <div class="col-md-7">
                                        <p class="font-size-ms">If you do not register your project, the freelancers you
                                            support
                                            will not be able to see the amount and content of other freelancers.</p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <span class="ml-1">$100</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="nda">
                                            <label class="custom-control-label" for="nda"></label>
                                        </div>
                                        <button class="btn btn-danger btn-sm disabled px-4 ml-n3">(NDA)</button>
                                    </div>

                                    <div class="col-md-7">
                                        <h5 class="font-size-sm font-weight-bold">Using chat is an indicator of the high
                                            success
                                            of the project. Unlimited chats by registration</h5>
                                        <p class="font-size-ms">A confidentiality agreement is a contract between a
                                            client
                                            and a
                                            freelancer to maintain the confidential information required to perform the
                                            project
                                            work. <a href="#" class="btn btn-sm btn-danger font-size-xs py-1">Caution <i
                                                    class="fa fa-caret-right ml-1"></i></a></p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <span class="ml-1">$100</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-3 pr-md-0">
                                        <div class="custom-control custom-control-inline custom-checkbox align-middle">
                                            <input type="checkbox" class="custom-control-input" id="Urgent">
                                            <label class="custom-control-label" for="Urgent"></label>
                                        </div>
                                        <button class="btn btn-warning btn-sm px-4 ml-n3">Urgent</button>
                                    </div>

                                    <div class="col-md-7">
                                        <p class="font-size-ms">This service is a service that allows freelancers to
                                            quickly
                                            process your project, including quick response of your project, urgent project,
                                            error (bug), change, updates.</p>
                                    </div>

                                    <div class="col-md-2 text-md-right pl-md-0">
                                        <div class="font-size-md">
                                            <span class="ml-1">$100</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <h5 class="font-weight-bold">Total (VAT excluded): $0</h5>
                            </div>

                            <p class="font-size-sm mb-0"><i class="fa fa-warning text-danger mr-2"></i> Freelancers who
                                apply
                                for the project may be subject to sanctions if they direct e-mails by posting e-mails, wire
                                /
                                wireless numbers, etc.</p>
                        </div>
                    </div>
                    <input type="submit" value="Post Contest" class="btn btn-primary">
                </form>
            </div>

            <div class="col-md-3">
                <img src="{{ url('assets/img/pages/home/illustration-1.png') }}" alt="Illustration"
                    class="img-fluid mb-4">

                <div class="card card-bordered rounded-xl shadow mb-4">
                    <div class="card-header text-center py-4">
                        <h6 class="font-size-sm font-weight-bold">ITS FREE TO POST A PROJECT</h6>
                        <p class="font-size-sm font-weight-bold mb-0">
                            <i class="fa fa-paper-plane mr-1"></i>
                            Project Free Easy Registration
                        </p>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled font-size-ms">
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> Clear and
                                concise
                                name of your Project</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> Detail
                                description
                                of your Project</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> Provide images
                                or
                                PDF with specification of your Project</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> Feel free to
                                Post
                                the Project</li>
                            <li class="d-flex">
                                <i class="fa fa-check text-secondary mt-1 mr-1"></i>
                                <span>The project registration order is <b class="text-info">How the project works</b>
                                    &gt;
                                    <b class="text-info">Current status of the project</b> &gt; <b
                                        class="text-info">Detailed business contents</b> &gt; <b
                                        class="text-info">References and Notes</b> Refer to the example in
                                    order</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card card-bordered rounded-xl shadow mb-4">
                    <div class="card-header text-center py-4">
                        <h6 class="font-size-sm font-weight-bold">CONTEST WITHOUT REAL TIME PUBLIC FORUM IS PAST</h6>
                        <p class="font-size-sm font-weight-bold mb-0">
                            <i class="fa fa-forumbee mr-1"></i>
                            SIMULTANEOUS INTERVIEWS WITH APPLICANTS IN REAL-TIME IS 1: 1 VIDEO CHAT!
                        </p>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled font-size-ms">
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> Set a contest
                                prize
                                and open a real-time public forum.</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> In
                                Bluefreelancer
                                real-time video chat with clients chosen by the applicants through public forums to get the
                                best results</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> The selected
                                freelancer and client can save time, money, and results more than expected with real-time 1:
                                2 video chatting.</li>
                            <li class="d-flex mb-2"><i class="fa fa-check text-secondary mt-1 mr-1"></i> In case of
                                using
                                chat, you can use unlimited use at any time by registration (per case) from 5,000 KRW
                                (excluding VAT) for video chatting from the beginning to the end of the contest. Purchase of
                                chat is free until the end of the term.</li>
                            <li class="d-flex"><i class="fa fa-check text-secondary mt-1 mr-1"></i> For security
                                and
                                security reasons, video chat must use Google Chrome. Use of Internet Explorer (IE) may be
                                restricted.</li>
                        </ul>

                        <div class="text-center">
                            <button class="btn btn-secondary btn-sm">Contest Free Registration</button>
                        </div>
                    </div>
                </div>

                <div class="card card-bordered rounded-xl shadow mb-4">
                    <div class="card-header text-center py-4">
                        <h6 class="font-size-sm font-weight-bold">HOW TO WRITE PROJECT CONTENTS</h6>
                        <p class="font-size-sm font-weight-bold mb-0">
                            Example Project Content
                            <span class="badge badge-secondary">See example</span>
                        </p>
                    </div>

                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
