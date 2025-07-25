  <div class="accordion" id="accordionExample">
      <div class="accordion-item">
          <h2 class="accordion-header">
              <button class="accordion-button btn-primary" style="color: #FFF;" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  <strong style="color: #FFF;"><i class="fa fa-check"></i></strong> Personal
                  Information
              </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
              <div class="row p-1 mt-3">

                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">F name</span>
                          <input type="text" class="form-control" aria-label="Sizing example input" name="first_name"
                              aria-describedby="inputGroup-sizing-default" placeholder="first name" required>
                      </div>
                  </div>
                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">L name</span>
                          <input type="text" class="form-control" aria-label="Sizing example input" name="last_name"
                              aria-describedby="inputGroup-sizing-default" placeholder="last name" required>
                      </div>
                  </div>
                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">Mid name</span>
                          <input type="text" class="form-control" aria-label="Sizing example input"
                              name="middle_name" aria-describedby="inputGroup-sizing-default" placeholder="middle name">

                      </div>
                  </div>
                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">Gender</span>
                          <select class="form-control" aria-label="Sizing example input" name="gender"
                              aria-describedby="inputGroup-sizing-default">
                              <option value="" selected disabled>--gender--</option>
                              <option value="M">Male</option>
                              <option value="F">Female</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">Birth Date</span>
                          <input type="date" class="form-control" id="code" aria-label="code"
                              name="date_of_birth" aria-describedby="inputGroup-sizing-default">
                      </div>
                  </div>
                  <div class="col-4 mb-3">
                      <div class="input-group mb-3">
                          <span class="input-group-text" id="inputGroup-sizing-default">NIN</span>
                          <input type="text" class="form-control" id="national_id_number" name="national_id_number"
                              aria-label="National ID Number" aria-describedby="inputGroup-sizing-default"
                              maxlength="20" pattern="\d{20}" placeholder="Enter 20-digit NIDA number"
                              title="NIDA number must be exactly 20 digits">
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="accordion-item mt-2">

          <h2 class="accordion-header">
              <button class="accordion-button btn-primary collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color: #FFF;">
                  <strong style="color: #FFF;"><i class="fa fa-check"></i></strong> Contact
                  Information
              </button>
          </h2>

          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">

                  <div class="row p-1">

                      <div class="col-4 mb-3">
                          <div class="input-group mb-3">
                              <span class="input-group-text">Email</span>
                              <input type="email" class="form-control" name="email" placeholder="email">
                          </div>
                      </div>
                      <div class="col-4 mb-3">
                          <div class="input-group mb-3">
                              <span class="input-group-text">Phone 1</span>
                              <input type="text" class="form-control" name="phone_number"
                                  placeholder="phone number" max="13">
                          </div>
                      </div>

                      <div class="col-4 mb-3">
                          <div class="input-group mb-3">
                              <span class="input-group-text">Name</span>
                              <input type="text" class="form-control" name="emergency_contact_name"
                                  placeholder="emergency contact name">
                          </div>
                      </div>

                      <div class="col-4 mb-3">
                          <div class="input-group mb-3">
                              <span class="input-group-text">Phone 2</span>
                              <input type="text" class="form-control" name="emergency_contact_phone"
                                  placeholder="emergency number" maxlength="13">
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <div class="accordion-item mt-2">

              <h2 class="accordion-header">
                  <button class="accordion-button btn-primary collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseTwo"
                      style="color: #FFF;">
                      <strong style="color: #FFF;"><i class="fa fa-check"></i></strong> Employment
                      Information
                  </button>
              </h2>

              <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                      <div class="row p-1">

                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Role</span>
                                  <select class="form-control" name="role">
                                      <option value="" selected disabled>--role--</option>
                                      @foreach ($userRoles as $role)
                                          <option value="{{ $role->id }}">{{ $role->name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Department</span>
                                  <select class="form-control" name="department">
                                      <option value="" selected disabled>--department--</option>
                                      @foreach ($departments as $department)
                                          <option value="{{ $department->id }}">{{ $department->name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Title</span>
                                  <input type="text" class="form-control" name="job_title"
                                      placeholder="job title">
                              </div>
                          </div>

                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Date Hired</span>
                                  <input type="date" class="form-control" name="date_hired"
                                      value="{{ old('date_hired', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Empl Type</span>
                                  <select class="form-control" name="employment_type">
                                      <option value="" selected disabled>--employment type--</option>
                                      <option value="Contract">Contract</option>
                                      <option value="Intern">Intern</option>
                                      <option value="Permanent">Permanent</option>
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <div class="accordion-item mt-2">

              <h2 class="accordion-header">
                  <button class="accordion-button btn-primary collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseTwo"
                      style="color: #FFF;">
                      <strong style="color: #FFF;"><i class="fa fa-check"></i></strong> Address
                      Information
                  </button>
              </h2>

              <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                      <div class="row p-1">
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Address</span>
                                  <input type="text" class="form-control" name="address" placeholder="address">
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">City</span>
                                  <input type="text" class="form-control" name="city" placeholder="city">
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Region</span>
                                  <input type="text" class="form-control" name="region" placeholder="region">
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Country</span>
                                  <input type="text" class="form-control" name="country" placeholder="country">
                              </div>
                          </div>
                          <div class="col-4 mb-3">
                              <div class="input-group mb-3">
                                  <span class="input-group-text">Postal code</span>
                                  <input type="text" class="form-control" name="postal_code"
                                      placeholder="postal_code">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="accordion-item mt-2">

                  <h2 class="accordion-header">
                      <button class="accordion-button btn-primary collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseTwo"
                          style="color: #FFF;">
                          <strong style="color: #FFF;"><i class="fa fa-check"></i></strong> Payment
                          Information
                      </button>
                  </h2>

                  <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">

                          <div class="row p-1">

                            <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">Salary</span>
                                      <input type="text" class="form-control" name="salary_amount"
                                          placeholder="salary amount">
                                  </div>
                              </div>

                              <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">Bank Name</span>
                                      <input type="text" class="form-control" name="bank_name"
                                          placeholder="bank name">
                                  </div>
                              </div>
                              <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">Acc No</span>
                                      <input type="text" class="form-control" name="bank_account_number"
                                          placeholder="bank account number">
                                  </div>
                              </div>
                              <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">TIN</span>
                                      <input type="text" id="tin" name="tax_identification_number"
                                          class="form-control" placeholder="Enter 9-digit TIN" maxlength="9"
                                          pattern="\d{9}" title="TIN must be exactly 9 digits">
                                  </div>
                              </div>
                              <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">Social Security</span>
                                      <select class="form-control" name="social_security_name">
                                          <option value="" selected disabled>--select--</option>
                                          <option value="NSSF">NSSF</option>
                                          <option value="PSSSF">PSSSF</option>
                                          <option value="NHIF/UHIA">NHIF/UHIA</option>
                                          <option value="WCF">WCF</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-4 mb-3">
                                  <div class="input-group mb-3">
                                      <span class="input-group-text">Security Number</span>
                                      <input type="text" class="form-control" name="social_security_number"
                                          placeholder="social security number">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row mt-3 p-1">
                  <div class="col-12">
                      <button type="submit" class="btn btn-primary float-start"><i class="fa fa-save"></i> Save
                          Data</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
