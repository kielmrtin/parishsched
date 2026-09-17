{{-- Reservation Detail Modal --}}
<div id="rdm-backdrop" onclick="closeResDetail()"></div>
<div id="rdm-modal">
    <div class="rdm-panel">
        <div class="ps-hdr">
            <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
            <span class="ps-parish">St. John the Baptist Parish</span>
            <span class="ps-dot"></span>
            <span class="ps-loc">Tiaong, Quezon</span>
        </div>
        <div class="rdm-head">
            <div class="rdm-eyebrow">Reservation Details</div>
            <div class="rdm-name" id="rdm-name"></div>
            <div class="rdm-sub" id="rdm-sub"></div>
            <div class="rdm-badges" id="rdm-badges"></div>
        </div>
        <div class="rdm-body">
            <div class="rdm-section">
                <div class="rdm-two-col">
                    <div>
                        <div class="rdm-sec-title">Contact</div>
                        <div class="rdm-row">
                            <div class="rdm-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                            <div><div class="rdm-label">Email</div><div class="rdm-value" id="rdm-email"></div></div>
                        </div>
                        <div class="rdm-row">
                            <div class="rdm-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.07 1.2 2 2 0 012.06 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></div>
                            <div><div class="rdm-label">Phone</div><div class="rdm-value" id="rdm-phone"></div></div>
                        </div>
                    </div>
                    <div>
                        <div class="rdm-sec-title">Schedule</div>
                        <div class="rdm-row">
                            <div class="rdm-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                            <div><div class="rdm-label">Date</div><div class="rdm-value" id="rdm-date"></div><div class="rdm-value-sub" id="rdm-day"></div></div>
                        </div>
                        <div class="rdm-row">
                            <div class="rdm-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                            <div><div class="rdm-label">Time</div><div class="rdm-value" id="rdm-time"></div></div>
                        </div>
                        <div class="rdm-row" id="rdm-off-row">
                            <div class="rdm-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                            <div><div class="rdm-label">Officiant</div><div class="rdm-value" id="rdm-officiant"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rdm-section" id="rdm-bap-sec" style="display:none">
                <div class="rdm-sec-title">Baptism Details</div>
                <div class="rdm-det-grid">
                    <div class="rdm-det-cell rdm-det-full">
                        <div class="rdm-det-label">Child's Name</div>
                        <div class="rdm-det-value" id="rdm-bap-child">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Date of Birth</div>
                        <div class="rdm-det-value" id="rdm-bap-dob">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Father's Name</div>
                        <div class="rdm-det-value" id="rdm-bap-father">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Mother's Name</div>
                        <div class="rdm-det-value" id="rdm-bap-mother">—</div>
                    </div>
                </div>
            </div>
            <div class="rdm-section" id="rdm-wed-sec" style="display:none">
                <div class="rdm-sec-title">Wedding Details</div>
                <div class="rdm-det-grid">
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Groom</div>
                        <div class="rdm-det-value" id="rdm-wed-groom">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Bride</div>
                        <div class="rdm-det-value" id="rdm-wed-bride">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Pre-Cana Seminar Date</div>
                        <div class="rdm-det-value" id="rdm-wed-seminar">—</div>
                    </div>
                    <div class="rdm-det-cell rdm-det-full" id="rdm-wed-sac-cell">
                        <div class="rdm-det-label">Kumpisal / Kumpil / Binyag</div>
                        <div class="rdm-det-value" id="rdm-wed-sacrament">—</div>
                    </div>
                </div>
            </div>
            <div class="rdm-section" id="rdm-fun-sec" style="display:none">
                <div class="rdm-sec-title">Funeral Details</div>
                <div class="rdm-det-grid">
                    <div class="rdm-det-cell rdm-det-full">
                        <div class="rdm-det-label">Name of Deceased</div>
                        <div class="rdm-det-value" id="rdm-fun-deceased">—</div>
                    </div>
                    <div class="rdm-det-cell">
                        <div class="rdm-det-label">Marital Status</div>
                        <div class="rdm-det-value" id="rdm-fun-marital">—</div>
                    </div>
                </div>
            </div>
            <div class="rdm-section" id="rdm-notes-sec" style="display:none">
                <div class="rdm-sec-title">Notes</div>
                <div class="rdm-notes" id="rdm-notes"></div>
            </div>
            <div class="rdm-section" id="rdm-att-sec" style="display:none">
                <div class="rdm-sec-title">Attachments</div>
                <div id="rdm-att-list"></div>
            </div>
        </div>
        <div class="rdm-footer">
            <form id="rdm-app-form" method="POST" action="{{ route('admin.handle') }}" style="display:contents">
                @csrf
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="reservation_id" id="rdm-app-id">
                <input type="hidden" name="status" value="approved">
                <input type="hidden" name="admin_note" value="">
                <input type="hidden" name="redirect_section" value="{{ $section }}">
                <button type="submit" class="rdm-foot-btn rdm-approve" id="rdm-app-btn">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Approve
                </button>
            </form>
            <form id="rdm-dec-form" method="POST" action="{{ route('admin.handle') }}" style="display:contents">
                @csrf
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="reservation_id" id="rdm-dec-id">
                <input type="hidden" name="status" value="declined">
                <input type="hidden" name="admin_note" value="">
                <input type="hidden" name="redirect_section" value="{{ $section }}">
                <button type="submit" class="rdm-foot-btn rdm-decline" id="rdm-dec-btn">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Decline
                </button>
            </form>
            <button type="button" class="rdm-foot-btn rdm-close-btn" onclick="closeResDetail()">Close</button>
        </div>
    </div>
</div>
