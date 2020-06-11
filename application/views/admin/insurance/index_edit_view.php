<input type='hidden' id='contact_id' value='<?= $contact['contact_id']; ?>' >

<div class='row'>
	<div class="col-md-10 col-md-offset-1">
		<div class="form-group row">
			<label class="control-label col-sm-3" for="date">資料</label>
			<div class="col-sm-9">
				<?= $contact['name_dharma']; ?> (<?= $contact['name_malay']; ?>) || NRIC: <?= $contact['nric']; ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Group Medical</label>
			<div class="col-sm-9">
				<input type="radio" name='group_medical' id='group_medical_yes' value="1" <?= ($contact['group_medical']) ? "checked" : ""; ?>><label for="group_medical_yes">Yes</label>
				<input type="radio" name='group_medical' id='group_medical_no' value="0" <?= ($contact['group_medical']) ? "" : "checked"; ?>><label for="group_medical_no">No</label>
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Group PA</label>
			<div class="col-sm-9">
				<input type="radio" name='group_pa' id='group_pa_yes' value="1" <?= ($contact['group_pa']) ? "checked" : ""; ?>><label for="group_pa_yes">Yes</label>
				<input type="radio" name='group_pa' id='group_pa_no' value="0" <?= ($contact['group_pa']) ? "" : "checked"; ?>><label for="group_pa_no">No</label>
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Additional Insurance</label>
			<div class="col-sm-9">
				<input type="text" name='a_max' id='a_max' value="<?= ($contact['a_max'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Additional Insurance Price (RM)</label>
			<div class="col-sm-9">
				<input type="text" name='a_max_price' id='a_max_price' value="<?= ($contact['a_max_price'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Policy Number</label>
			<div class="col-sm-9">
				<input type="text" name='policy_number' id='policy_number' value="<?= ($contact['policy_number'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Entry Age</label>
			<div class="col-sm-9">
				<input type="text" name='entry_age' id='entry_age' value="<?= ($contact['entry_age'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Date Issue</label>
			<div class="col-sm-9">
				<input type="date" name='date_issue' id='date_issue' value="<?= ($contact['date_issue'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Date Effective</label>
			<div class="col-sm-9">
				<input type="date" name='date_effective' id='date_effective' value="<?= ($contact['date_effective'])?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="control-label col-sm-3" for="duration">Date Expiry</label>
			<div class="col-sm-9">
				<input type="date" name='date_expiry' id='date_expiry' value="<?= ($contact['date_expiry'])?>">
			</div>
		</div>

	</div>
</div>

