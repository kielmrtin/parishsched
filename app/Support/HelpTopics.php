<?php

namespace App\Support;

/**
 * Page-by-page help shown by the "?" button (panel revision: Help Menu,
 * Ma'am Imelda). The topic is picked from the current route, and on the
 * admin page from the open section and the signed-in role.
 */
class HelpTopics
{
    /** @return array<string, array{title: string, steps: string[]}> */
    public static function customer(): array
    {
        return [
            'home' => ['title' => 'Getting started', 'steps' => [
                'Create an account (Create Account) and verify your phone number.',
                'Press Reserve Now to book a baptism, wedding or funeral.',
                'Check Schedule for Mass times and open dates.',
                'Use the calendar icon (My Reservations) to follow your requests.',
            ]],
            'about' => ['title' => 'About the parish', 'steps' => [
                'Read about the parish, its priests and ministries.',
                'Press View Mass Schedule for Mass times.',
                'Press Plan a Celebration to start a reservation.',
            ]],
            'schedule' => ['title' => 'Reading the schedule', 'steps' => [
                'The calendar shows Mass times and parish events.',
                'Pick a date to see what is scheduled that day.',
                'To book a sacrament, go to Reserve Now. Only open dates can be picked there.',
            ]],
            'contact' => ['title' => 'Sending an inquiry', 'steps' => [
                'Fill in your name, email and message.',
                'Press Send. The parish office replies by email.',
                'For urgent concerns, call the parish office number in the footer.',
            ]],
            'reserve' => ['title' => 'How to book', 'steps' => [
                'Pick the sacrament, then a date on the calendar. Only open days can be picked.',
                'Choose one of the available times.',
                'Fill in the details, then upload every required document. Each one gets a green check when attached.',
                'Press Submit. You will get an email and SMS once the parish office reviews it.',
                'The same person cannot have two active reservations for the same sacrament.',
            ]],
            'my-reservations' => ['title' => 'Your reservations', 'steps' => [
                'Each card shows the status: Pending, Approved, Declined or Cancelled.',
                'Read the parish office note for instructions.',
                'On an approved reservation, press Pay with GCash to send the church donation online.',
                'Press Request Cancellation if you can no longer attend.',
            ]],
            'give' => ['title' => 'Donating through GCash', 'steps' => [
                'Send the amount to the parish GCash number shown on the left.',
                'Take a screenshot of the receipt with the reference number.',
                'Enter the amount, purpose and reference number, attach the screenshot, then press Submit payment.',
                'Your payment shows as Pending verification until the parish office confirms it.',
            ]],
            'login' => ['title' => 'Signing in', 'steps' => [
                'Enter the email and password you registered with.',
                'Forgot your password? Press Forgot password to get a reset link by email.',
                'No account yet? Open the Create Account tab.',
            ]],
            'register' => ['title' => 'Creating an account', 'steps' => [
                'Enter your name and email. The email is checked right away; an email can only be used once.',
                'Enter your address and a password of at least 8 characters.',
                'Last, enter your phone number, press Send OTP, and type the 6-digit code sent to your phone.',
                'Press Create Account.',
            ]],
            'forgot-password' => ['title' => 'Resetting your password', 'steps' => [
                'Enter the email you registered with.',
                'Open the reset link sent to your email.',
                'Choose a new password and sign in again.',
            ]],
            'reset-password' => ['title' => 'Choosing a new password', 'steps' => [
                'Enter a new password of at least 8 characters, twice.',
                'Press Reset Password, then sign in with the new password.',
            ]],
        ];
    }

    /** @return array<string, array{title: string, steps: string[]}> Keyed by admin section. */
    public static function admin(): array
    {
        return [
            'login' => ['title' => 'Signing in to the admin panel', 'steps' => [
                'Enter the admin or secretary username and password.',
                'The secretary sees Reservations, Schedule, Customers and Donations only.',
            ]],
            'overview' => ['title' => 'Overview', 'steps' => [
                'The cards show today\'s numbers: pending requests, upcoming events and donations.',
                'Press a card or a sidebar item to open that section.',
            ]],
            'reports' => ['title' => 'Reports', 'steps' => [
                'Read Recommended actions first: each one is a decision backed by the numbers below it.',
                'Pick a date range to change every chart and table.',
                'Log attendance for past events in the Event attendance log.',
                'Press Export PDF to download the report.',
            ]],
            'reservations' => ['title' => 'Reviewing a request', 'steps' => [
                'Open View details on a pending request.',
                'Check the Requirements badge. Missing means a document was not uploaded.',
                'Approve or decline, adding a note if needed. The customer is notified by email and SMS.',
                'Assign an officiant from the Officiant list.',
            ]],
            'schedule' => ['title' => 'Using the schedule', 'steps' => [
                'Click a date on the calendar.',
                'The right side lists every time for that day. A blank time is free.',
                'Click a person\'s name to see their reservation details.',
                'Use the type buttons to highlight one sacrament, or switch to List view.',
            ]],
            'customers' => ['title' => 'Managing customers', 'steps' => [
                'Search by name or email.',
                'Open a customer to see their reservations.',
                'Disable an account to stop new reservations from it.',
            ]],
            'announcements' => ['title' => 'Posting announcements', 'steps' => [
                'Write a title and message, and add an image if needed.',
                'Press Post. It appears on the home page and in the app.',
                'Delete an announcement when it is no longer needed.',
            ]],
            'donations' => ['title' => 'Recording donations', 'steps' => [
                'Choose the payment method. GCash needs the reference number; a cheque needs its number and bank.',
                'Online GCash payments arrive as Pending verification. Check the screenshot and your GCash history, then press Verify or Reject.',
                'Cheques start Uncleared. Press Mark cleared once the bank clears them.',
                'Totals count only received, verified and cleared money.',
            ]],
        ];
    }

    /**
     * The topic for the current request, plus the other topics of the same
     * audience for the "All help topics" list.
     *
     * @return array{audience: string, current: array{title: string, steps: string[]}, others: array}|null
     */
    public static function forRequest(?string $adminSection = null): ?array
    {
        $route = request()->route()?->getName();

        if ($route === 'admin.index' || $adminSection !== null) {
            $topics = self::admin();
            $loggedIn = session('admin_logged_in') === true;
            $key = $loggedIn ? ($adminSection ?: 'overview') : 'login';
            if ($loggedIn && session('admin_role') === 'secretary') {
                $topics = array_intersect_key($topics, array_flip(['reservations', 'schedule', 'customers', 'donations']));
            }
            $audience = !$loggedIn ? 'Admin' : (session('admin_role') === 'secretary' ? 'Secretary' : 'Admin');
        } else {
            $topics = self::customer();
            $key = match ($route) {
                'home' => 'home',
                'about' => 'about',
                'schedule' => 'schedule',
                'contact' => 'contact',
                'reservation.index' => 'reserve',
                'reservation.my' => 'my-reservations',
                'donate.index' => 'give',
                'login' => 'login',
                'register' => 'register',
                'password.request' => 'forgot-password',
                'password.reset' => 'reset-password',
                default => 'home',
            };
            $audience = 'Customer';
        }

        $current = $topics[$key] ?? null;
        if (!$current) {
            return null;
        }

        $others = $topics;
        unset($others[$key]);
        if ($key !== 'login') {
            unset($others['login']);
        }

        return ['audience' => $audience, 'current' => $current, 'others' => $others];
    }
}
