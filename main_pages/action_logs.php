<div class="contents animate hide" id="action_logs_page">
    <div class="titled">
        <h2>Action Logs</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>A running audit trail of actions taken on your school's account (admissions, finance, academic, boarding, SMS, login).</p>
        </div>
        <div class="middle1">
            <div class="section_card">
                <p class="card_heading">Action Logs</p>
                <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:16px;">
                    <div>
                        <label for="al_from" style="display:block;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;">From</label>
                        <input type="date" id="al_from" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label for="al_to" style="display:block;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;">To</label>
                        <input type="date" id="al_to" class="form-control form-control-sm">
                    </div>
                    <div style="flex:1;min-width:180px;">
                        <label for="al_keyword" style="display:block;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;">Search</label>
                        <input type="text" id="al_keyword" class="form-control form-control-sm" placeholder="e.g. discount, admission, M-Pesa">
                    </div>
                    <div>
                        <label for="al_user" style="display:block;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;">User</label>
                        <select id="al_user" class="form-control form-control-sm">
                            <option value="">All Users</option>
                        </select>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="al_clear_filters">Clear</button>
                    </div>
                </div>
                <p class="text-muted" style="font-size:12px;" id="al_count"></p>
                <div id="al_list" style="max-height:520px;overflow-y:auto;"></div>
                <p id="al_no_results" class="hide text-center text-muted" style="padding:40px 0;">No log entries match the current filters.</p>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>
