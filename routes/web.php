<?php

use App\Http\Controllers\Auth\ForgotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
// Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestEntryController;
use App\Http\Controllers\ContestHandoverController;
use App\Http\Controllers\ContestPublicForumController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputeArbitrationController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\DisputeConversationController;
use App\Http\Controllers\EmployerProjectController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\FreelancerProjectController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\Payment\DepositController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\Project\ProjectManageController;
use App\Http\Controllers\Project\ProposalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectOfferController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\ShowcaseLikeController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ExperienceController;
use App\Http\Controllers\User\EducationController;
use App\Http\Controllers\User\CertificationController;
use App\Http\Controllers\User\PortfolioController;
use App\Http\Controllers\User\ProfCertificationController;
use App\Http\Controllers\User\PublicationController;
use App\Http\Controllers\User\SkillController;
use App\Models\Contest;
use App\Models\Feedback;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Lang
Route::get('/locale/{lang}', [LangController::class, 'set'])->name('locale');
// For Home
Route::get('/', function () {
    $projects = Project::orderBy('created_at', 'DESC')->limit(4)->get();
    $contests = Contest::orderBy('created_at', 'DESC')->limit(4)->get();
    return view('home', [
        'projects' => $projects,
        'contests' => $contests,
    ]);
})->name('home');
Route::middleware(['guest'])->group(function () {
    // For Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    // For Registration
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    // Forgot Password
    Route::get('/forgot', [ForgotController::class, 'index']);
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    });
    Route::post('/forgot-email', [ForgotController::class, 'email'])->name('forgot.email');
    Route::post('/forgot-store', [ForgotController::class, 'store'])->name('forgot.store');
});

// For Mail 
// => For Email Verify (View)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// => For After Email Verification Redirect
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
// => For Email Resend Verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // For Setting
    Route::get('/settings', function () {
        return view('user.setting');
    })->name('settings');
    Route::get('/setting/profile', function () {
        return view('user.setting.profile');
    })->name('/setting/profile');
    Route::post('/setting/profile', [SettingController::class, 'profile'])->name('/setting/profile');
    Route::get('/setting/notification', function () {
        return view('user.setting.notification');
    })->name('/setting/notification');
    Route::post('/setting/email', [SettingController::class, 'email'])->name('/setting/email');
    Route::get('/setting/account/notify-all-freelancer', [SettingController::class, 'notifyAllFreelancers'])->name('setting.account.notify-all-freelancer');
    Route::get('/setting/account/notify-all-projects', [SettingController::class, 'notifyAllProjects'])->name('setting.account.notify-all-projects');
    Route::get('/setting/account', function () {
        return view('user.setting.account');
    })->name('/setting/account');
    Route::post('/setting/account', [SettingController::class, 'account'])->name('/setting/account');
    Route::get('/setting/password', function () {
        return view('user.setting.password');
    })->name('/setting/password');
    Route::post('/setting/password', [SettingController::class, 'passwordChange'])->name('/setting/password');

    // For User Main Profile
    Route::get('/profile', function (Request $request) {
        if ($request->outsourcer) {
            $user = User::find(Crypt::decryptString($request->outsourcer));
        } else {
            $user = User::find(auth()->id());
        }

        if ($request->view == 'client') {
            $feedbacks = Feedback::where('type', 2)->where('user_to', auth()->id())->with('project')->get();
        } else {
            $feedbacks = Feedback::where('type', 1)->where('user_to', auth()->id())->with('project')->get();
        }

        $experiences = App\Models\User::find($user->id)->experiences()->get();
        $education = App\Models\User::find($user->id)->education()->get();
        $certifications = App\Models\User::find($user->id)->certifications()->get();
        $publications = App\Models\User::find($user->id)->publications()->get();
        $portfolios = App\Models\User::find($user->id)->portfolios()->get();
        return view('user.profile', [
            'experiences' => $experiences,
            'education' => $education,
            'certifications' => $certifications,
            'publications' => $publications,
            'portfolios' => $portfolios,
            'user' => $user,
            'feedbacks' => $feedbacks,
        ]);
    })->name('profile');
    Route::post('/profile/hourly_rate', [ProfileController::class, 'hourlyRate'])->name('/profile/hourly_rate');
    Route::post('/profile/coverImg', [ProfileController::class, 'coverImage'])->name('/profile/coverImg');
    Route::post('/profile/profileImg', [ProfileController::class, 'profileImage'])->name('/profile/profileImg');
    Route::post('/profile/prof_headline', [ProfileController::class, 'professionHeadline'])->name('/profile/prof_headline');
    Route::post('/profile/description', [ProfileController::class, 'description'])->name('/profile/description');
    Route::post('/profile/skill/store', [ProfileController::class, 'skillStore'])->name('skill.store');
    Route::post('/profile/profCertification/store', [ProfileController::class, 'profCertificationStore'])->name('profCertification.store');

    // => Portfolio
    Route::post('/profile/portfolio', [PortfolioController::class, 'store'])->name('/profile/portfolio');
    Route::get('/profile/portfolio/edit/{id}', [PortfolioController::class, 'show'])->name('/profile/portfolio/edit');
    Route::post('/profile/portfolio/update', [PortfolioController::class, 'update'])->name('/profile/portfolio/update');

    // => Experience
    Route::post('/profile/experience', [ExperienceController::class, 'store'])->name('/profile/experience');
    Route::get('/profile/experience/edit/{id}', [ExperienceController::class, 'show'])->name('/profile/experience/edit');
    Route::post('/profile/experience/update', [ExperienceController::class, 'update'])->name('/profile/experience/update');
    Route::delete('/profile/experience/delete/{experience}', [ExperienceController::class, 'destory'])->name('experience.destory');

    // => Education
    Route::post('/profile/education', [EducationController::class, 'store'])->name('/profile/education');
    Route::get('/profile/education/edit/{id}', [EducationController::class, 'show'])->name('/profile/education/edit');
    Route::post('/profile/education/update', [EducationController::class, 'update'])->name('/profile/education/update');
    Route::delete('/profile/education/delete/{education}', [EducationController::class, 'destory'])->name('education.destory');

    // => Certification
    Route::post('/profile/certification', [CertificationController::class, 'store'])->name('/profile/certification');
    Route::get('/profile/certification/edit/{id}', [CertificationController::class, 'show'])->name('/profile/certification/edit');
    Route::post('/profile/certification/update', [CertificationController::class, 'update'])->name('/profile/certification/update');
    Route::delete('/profile/certification/delete/{certification}', [CertificationController::class, 'destory'])->name('certification.destory');

    // => Publications
    Route::post('/profile/publication', [PublicationController::class, 'store'])->name('/profile/publication');
    Route::get('/profile/publication/edit/{id}', [PublicationController::class, 'show'])->name('/profile/publication/edit');
    Route::post('/profile/publication/update', [PublicationController::class, 'update'])->name('/profile/publication/update');
    Route::delete('/profile/publication/delete/{publication}', [PublicationController::class, 'destory'])->name('publication.destory');

    // => Skills
    Route::get('/profile/skill', [SkillController::class, 'index'])->name('/profile/skill');
    Route::get('/profile/skill/show/{id}', [SkillController::class, 'show'])->name('/profile/skill/show');

    // =>Prof Certifications
    Route::get('/profile/profCertifications', [ProfCertificationController::class, 'index'])->name('/profile/skill');

    // Projects
    // => Post Project
    Route::get('/post-project', function () {
        return view('project.post-project');
    })->name('post-project');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store');
    // => Project Update
    Route::post('/project/update/{id}', [ProjectController::class, 'update'])->name('project.update');
    // => Project Listing
    Route::get('/project-listing', [ProjectController::class, 'index'])->name('project-listing');
    // =>Project Details
    Route::get('/project-details/{id}', [ProjectController::class, 'show'])->name('project.show');

    // ==> Category
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    // ==> Sub Category
    Route::get('/subcategory/show/{id}', [SubCategoryController::class, 'show'])->name('subcategory.show');
    // => Bids
    // ==> Place Bid
    Route::post('/bid/store', [BidController::class, 'store'])->name('bid.store');
    // ==> Bid Destory
    Route::delete('/bid/destory/{id}', [BidController::class, 'destory'])->name('bid.destory');
    // ==>show Bid or get Record for update Bid
    Route::get('/bid/show/{id}', [BidController::class, 'show'])->name('bid.show');
    // ==>update Bid
    Route::post('/bid/update/{id}', [BidController::class, 'update'])->name('bid.update');
    // => My Project
    // ==> Employer
    Route::get('/project/my-project/employer', [EmployerProjectController::class, 'index'])->name('my-project.employer');
    Route::get('/project/my-project/employer/open-projects', [EmployerProjectController::class, 'openProject'])->name('my-project.employer.open-projects');
    Route::get('/project/my-project/employer/work-projects', [EmployerProjectController::class, 'workProject'])->name('my-project.employer.work-projects');
    Route::get('/project/my-project/employer/past-projects', [EmployerProjectController::class, 'pastProject'])->name('my-project.employer.past-projects');
    Route::get('/project/my-project/employer/open-contests', [EmployerProjectController::class, 'openContest'])->name('my-project.employer.open-contests');
    Route::get('/project/my-project/employer/awarded-contests', [EmployerProjectController::class, 'awardedContest'])->name('my-project.employer.awarded-contests');
    // ==> Freelancer
    Route::get('/project/my-project/freelancer', [FreelancerProjectController::class, 'index'])->name('my-project.freelancer');
    Route::get('/project/my-project/freelancer/open-projects', [FreelancerProjectController::class, 'openProject'])->name('my-project.freelancer.open-projects');
    Route::get('/project/my-project/freelancer/work-projects', [FreelancerProjectController::class, 'workProject'])->name('my-project.freelancer.work-projects');
    Route::get('/project/my-project/freelancer/past-projects', [FreelancerProjectController::class, 'pastProject'])->name('my-project.freelancer.past-projects');
    Route::get('/project/my-project/freelancer/active-contests', [FreelancerProjectController::class, 'activeContest'])->name('my-project.freelancer.active-contests');
    Route::get('/project/my-project/freelancer/past-contests', [FreelancerProjectController::class, 'pastContest'])->name('my-project.freelancer.past-contests');
    // => Project Management
    Route::get('project/{id}/manage', [ProjectManageController::class, 'index'])->name('project.manage');
    Route::get('project/{id}/manage/proposals', [ProjectManageController::class, 'proposal'])->name('project.manage.proposals');
    Route::get('project/{id}/manage/milestone-manage', [ProjectManageController::class, 'manage'])->name('project.manage.milestone');
    Route::get('project/{id}/manage/modify', [ProjectManageController::class, 'modify'])->name('project.manage.modify');
    // ==> Proposal
    // ===> Get All Proposal as Per Project
    Route::get('/project/my-project/{project_id}/proposal', [ProposalController::class, 'index'])->name('my-project.proposal');
    // ===> Send Request to Freelancer of Proposal
    Route::post('/project/my-project/{project_id}/proposal/send-request', [ProposalController::class, 'store'])->name('my-project.send-request');
    Route::post('/project/conversation', [ProposalController::class, 'conversation'])->name('my-project.conversation');
    // ===> Proposal Approved by Freelancer
    Route::get('/project/my-project/{bid_id}/proposal/approve', [ProposalController::class, 'update'])->name('proposal.update');
    // ===> Proposal Rejected by Freelancer
    Route::get('/project/my-project/{bid_id}/proposal/reject', [ProposalController::class, 'destory'])->name('proposal.destory');
    // Milestone deposit OR Reject
    Route::post('/milestone/depositOrReject/{id}', [MilestoneController::class, 'depositOrReject'])->name('milestone.depositOrReject');
    // Milestone Release OR Refund OR Dispute
    Route::post('/milestone/rrd/{id}', [MilestoneController::class, 'ReleaseRefundDispute'])->name('milestone.rrd');
    Route::post('/milestone/destory/{id}', [MilestoneController::class, 'destory'])->name('milestone.destory');
    Route::post('/milestone/deposit', [MilestoneController::class, 'deposit'])->name('milestone.deposit');
    Route::get('/inbox', function () {
        return view('messages');
    })->name('inbox');
    // ==> Project Offer
    // Route::post('/project/offer/milestone-deposit', [MilestoneController::class, 'deposit'])->name('project-offer.milestone-deposit');
    Route::post('/project/offer/milestone-deposit', [ProjectOfferController::class, 'milestoneDeposit'])->name('project-offer.milestone-deposit');
    Route::post('/project/offer/{id}', [ProjectOfferController::class, 'store'])->name('project-offer.store');
    // Chat Controller
    Route::get('friendsList/{id}', [ChatController::class, 'friendsList']);
    Route::post('singleChat', [ChatController::class, 'singleChat']);
    Route::post('send-message', [ChatController::class, 'send']);
    Route::post('seenMessage', [ChatController::class, 'seenMessage']);
    Route::get('messsageCount/{id}', [ChatController::class, 'messsageCount']);
    // Browse
    Route::get('/browse/directory', [BrowseController::class, 'directory']);
    Route::get('/browse/category', [BrowseController::class, 'category']);
    Route::get('/browse/category-details/{id}', [BrowseController::class, 'categoryDetails']);
    // Showcase
    Route::get('/showcase/registration', function () {
        return view('showcase.create');
    })->name('showcase.create');
    Route::post('/showcase/store', [ShowcaseController::class, 'store'])->name('showcase.store');
    Route::get('/showcases', [ShowcaseController::class, 'index'])->name('showcase.index');
    Route::get('/my-showcase', [ShowcaseController::class, 'myShowcase'])->name('showcase.my-showcase');
    Route::get('/showcase/show/{id}', [ShowcaseController::class, 'show'])->name('showcase.show');
    Route::get('/showcase/edit/{id}', [ShowcaseController::class, 'edit'])->name('showcase.edit');
    Route::post('/showcase/update/{id}', [ShowcaseController::class, 'update'])->name('showcase.update');
    Route::get('/showcase/delete/{id}', [ShowcaseController::class, 'destory'])->name('showcase.destory');
    // Showcase Like
    Route::get('/showcase/like/{id}', [ShowcaseLikeController::class, 'store'])->name('showcase_like.store');
    Route::get('/showcase/unlike/{id}', [ShowcaseLikeController::class, 'destory'])->name('showcase_like.destory');
    // Contest
    Route::get('/post-contest', function () {
        return view('contest.post-contest');
    })->name('post-contest');
    Route::post('/contest/store', [ContestController::class, 'store'])->name('contest.store');
    Route::get('/contest-listing', [ContestController::class, 'index'])->name('contest-listing');
    Route::get('/contest-details/{id}', [ContestController::class, 'show'])->name('contest-details');
    Route::get('/contest-edit/{id}', [ContestController::class, 'edit'])->name('contest-edit');
    Route::post('/contest/update/{id}', [ContestController::class, 'update'])->name('contest.update');
    Route::post('/contest/destory/{id}', [ContestController::class, 'destory'])->name('contest.destory');
    // ==> Contest Public Forum 
    Route::post('/contest/public-forum/store', [ContestPublicForumController::class, 'store'])->name('contest_public_forum.store');
    //  ==> Contest Entry
    Route::get('contest/entry/{id}', function ($id) {
        $contest = Contest::where('contest_id', $id)->first();
        return view('contest.contest-entry', [
            'contest' => $contest,
        ]);
    });
    Route::get('/contest/entry/detail/{id}', [ContestEntryController::class, 'show'])->name('contest-entry.detail');
    Route::post('/contest/entry/store', [ContestEntryController::class, 'store'])->name('contest-entry.store');
    Route::post('/contest/entry/accept/{id}', [ContestEntryController::class, 'update'])->name('contest-entry.accept');
    // Contest Handover
    Route::get('/contest/handover/{id}', [ContestHandoverController::class, 'index'])->name('contest-handover');
    Route::post('/contest/handover/store/{id}', [ContestHandoverController::class, 'store'])->name('contest-handover.store');
    // Support
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/show/{category}', [SupportController::class, 'show'])->name('support.show');
    // Fee && Charge
    Route::get('/fee-and-charge', function () {
        return view('fee-and-charge');
    })->name('fee-and-charge');
    // How to use
    Route::get('/how-to-use', function () {
        return view('how-to-use');
    })->name('how-to-use');
    // Payment
    // => Paypal Deposit
    Route::get('/payment/deposit/paypal', function () {
        return view('payment.deposit.paypal');
    })->name('payment.deposit.paypal');
    // Route::post('/payment/deposit/paypal/store', [DepositController::class, 'PaypalDeposit'])->name('PaypalDeposit');
    Route::get('/paypal-demo', function () {
        return view('paypal-demo');
    })->name('paypal-demo');
    Route::get('payment', [PayPalController::class, 'payment'])->name('payment');
    Route::get('cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
    Route::get('payment/success', [PayPalController::class, 'success'])->name('payment.success');
    // => Payment Verification
    Route::get('/payment/paypal/verify', function () {
        return view('payment.verify');
    })->name('payment.verify');
    // Project feedback
    Route::get('/project/{id}/feedback/{user}/{type}', function () {
        return view('project.feedback');
    })->name('project.feedback');
    Route::post('/project/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/project/feedback/show/{user}/{type}', [FeedbackController::class, 'show'])->name('feedback.show');
    // Paypal Payouts 
    Route::get('withdraw', function () {
        return view('payout.index');
    })->name('withdraw');
    Route::get('payout', [PayoutController::class, 'payout'])->name('payout');
    // Transaction History
    Route::get('transaction-history', [TransactionHistoryController::class, 'index'])->name('transaction-history.index');
    // Route::post('transaction-history/store', [TransactionHistoryController::class, 'store'])->name('transaction-history.store');
    // Financial Dashboard
    Route::get('financial-dashboard/employer', [FinancialDashboardController::class, 'employer'])->name('financial-dashboard.employer');
    Route::get('financial-dashboard/freelancer', [FinancialDashboardController::class, 'freelancer'])->name('financial-dashboard.freelancer');
    // Dispute
    Route::get('dispute/stage-one/{to}/{milestone_id}', [DisputeController::class, 'stageOne'])->name('dispute.stage-one');
    Route::get('dispute/stage-two/{id}', [DisputeController::class, 'stageTwo'])->name('dispute.stage-two');
    Route::get('dispute/stage-three/{id}', [DisputeController::class, 'stageThree'])->name('dispute.stage-three');
    Route::get('dispute/stage-four', function ()
    {
        return view('dispute.stage-four');
    })->name('dispute.stage-four');
    Route::post('dispute/store', [DisputeController::class, 'store'])->name('dispute.store');
    Route::post('dispute/new-offer', [DisputeController::class, 'newOffer'])->name('dispute.new-offer');
    Route::post('dispute/offer-accept', [DisputeController::class, 'acceptOffer'])->name('dispute.offer-accept');
    Route::post('dispute/cancel/{id}', [DisputeController::class, 'cancel'])->name('dispute.cancel');
    Route::post('dispute/conversation/store', [DisputeConversationController::class, 'store'])->name('dispute.conversation.store');
    Route::get('dispute/arbitration/store/{id}', [DisputeArbitrationController::class, 'store'])->name('dispute.arbitration.store');
    // For Logout
    Route::get('/logout', [LogoutController::class, 'store'])->name('logout');
});