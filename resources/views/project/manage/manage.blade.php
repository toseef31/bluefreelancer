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
        <h1 class="h5 font-weight-bold text-white">My Project Status</h1>
    </div>

    <section class="container py-5">
        <h2 class="font-weight-bold text-center pb-4"><span class="badge text-white bg-success-alt">Project</span>
            {{ $project->title }} </h2>

        <div class="card border-0 bg-primary mb-5">
            <div class="card-header">
                <ul class="nav nav-wider nav-pills nav-pills-light justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link font-weight-bold "
                            href="{{ route('project.manage.proposals', request()->route('id')) }}">Proposal</a>
                    </li>
                    <li class="nav-item mr-3" role="presentation">
                        <a class="nav-link font-weight-bold active" id="pills-management-tab"
                            href="{{ route('project.manage.milestone', request()->route('id')) }}">Management</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link font-weight-bold" id="pills-modify-tab"
                            href="{{ route('project.manage.modify', request()->route('id')) }}">Modify / Delete
                            Project</a>
                    </li>
                </ul>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-management" role="tabpanel"
                aria-labelledby="pills-management-tab">
                <div class="row">
                    <div class="col-lg-8">
                        <ul class="list-unstyled">
                            @if ($proposals->count())
                                @foreach ($proposals as $item)
                                    @if ($item->status == 2 || $item->status == 3)
                                        <li class="mb-4">
                                            <div class="card card-bordered card-body rounded-xl">
                                                <div class="row mb-4">
                                                    <div class="col-3 col-md-2">
                                                        <img class="img-fluid"
                                                            src="{{ $item->user->img == '' ? url('assets/img/pages/default.png') : url('uploads/users/' . $item->user->id . '/images/' . $item->user->img) }}"
                                                            width="96">
                                                    </div>
                                                    <div class="col-10">
                                                        <h4 class="font-size-lg text-primary pb-2">
                                                            <a href="#">{{ $item->user->username }}</a>
                                                        </h4>
                                                        <div class="d-flex">
                                                            <p class="pr-5 text-success">Approved</p>
                                                            <p>Completed in
                                                                {{ $project->currency == 'USD' ? '$' : '₩' }}{{ $item->budget }}
                                                                within {{ $item->day }} days</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form action="{{ route('milestone.deposit') }}" method="post"
                                                    class="my-4">
                                                    @csrf
                                                    <input type="hidden" name="deposit_project_id"
                                                        value="{{ request()->route('id') }}">
                                                    <input type="hidden" name="deposit_user_id"
                                                        value="{{ $item->user->id }}">
                                                    <input type="hidden" name="deposit_bid_id" value="{{ $item->id }}">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input type="text" name="deposit_name" id=""
                                                                class="form-control" placeholder="Deposit Description">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="deposit_amount"
                                                                placeholder="Deposit Amount" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="submit" name="milestone-deposit"
                                                                value="Deposit Milestone" class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="card border-0 bg-primary mb-4">
                                                    <div class="card-header">
                                                        <ul class="nav nav-pills nav-pills-light">
                                                            <li class="nav-item"><a class="nav-link active"
                                                                    href="#">Milestone</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                @if ($item->milestones->count())
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Amount Requested</th>
                                                                <th scope="col">Contents</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php($total = 0)
                                                            @foreach ($item->milestones as $milestone)
                                                                @if ($milestone->status == 2 || $milestone->status == 1)
                                                                    @php($total += $milestone->amount)
                                                                @endif

                                                                <tr>
                                                                    <td>
                                                                        {{ $project->currency == 'USD' ? '$' : '₩' }}{{ $milestone->amount }}
                                                                    </td>
                                                                    <td>{{ $milestone->name }}</td>
                                                                    <td>
                                                                        @if ($milestone->status == 1)
                                                                            Requested
                                                                        @elseif($milestone->status == 2)
                                                                            Deposit
                                                                        @elseif($milestone->status == 3)
                                                                            Rejected By Project Owner
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($milestone->status == 1)
                                                                            <form
                                                                                action="{{ route('milestone.depositOrReject', $milestone->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="project_id"
                                                                                    value="{{ request()->route('id') }}">
                                                                                <input type="submit"
                                                                                    class="btn btn-success bt-xs"
                                                                                    name="deposit" value="Deposit">
                                                                                <input type="submit"
                                                                                    class="btn btn-danger bt-xs"
                                                                                    name="reject" value="Reject">
                                                                            </form>
                                                                        @elseif($milestone->status == 2)
                                                                            <form action="#" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="project_id"
                                                                                    value="{{ request()->route('id') }}">
                                                                                <input type="submit"
                                                                                    class="btn btn-success bt-xs"
                                                                                    name="amount_release"
                                                                                    value="Amount Release">
                                                                                <input type="submit"
                                                                                    class="btn btn-danger bt-xs"
                                                                                    name="dispute" value="Dispute">
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <p>
                                                        <strong>Total:
                                                            {{ $project->currency == 'USD' ? '$' : '₩' }}{{ $total }}</strong>
                                                    </p>
                                                @else
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <span class="text-danger">Ops! no milestone
                                                                    found...</span>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                @endif
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-bordered card-body rounded-xl">
                            <h5 class="font-size-lg mb-4"><strong>What are the Milestone payments</strong></h5>
                            <h5 class="font-size-lg mb-3"><strong>Milestone are :</strong></h5>
                            <ul class="list-unstyled lh-3">
                                <li>
                                    <div class="icon border-info mx-1">
                                        <i class="fa fa-check text-info"></i>
                                    </div>
                                    <span class="text-info"><strong>Safe & Secure :</strong></span>
                                    <span>We hild your milestone until you decide to release them.</span>
                                </li>

                                <li>
                                    <div class="icon border-info mx-1">
                                        <i class="fa fa-check text-info"></i>
                                    </div>
                                    <span class="text-info"><strong>Refundable :</strong></span>
                                    <span>If you are dissatisfied or the JCM does not accept.</span>
                                </li>

                                <li>
                                    <div class="icon border-info mx-1">
                                        <i class="fa fa-check text-info"></i>
                                    </div>
                                    <span class="text-info"><strong>Controlled By you :</strong></span>
                                    <span>Release them only if you are 100% satisfied.</span>
                                </li>

                                <li>
                                    <div class="icon border-danger mx-1">
                                        <i class="fa fa-check text-danger"></i>
                                    </div>
                                    <span class="text-info"><strong>Please note that if you request a direct money
                                            transfer
                                            from a freelancer to avoid a fee, the client is at a considerable
                                            risk.</strong></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection