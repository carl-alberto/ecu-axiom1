<div class="ecu_findaprogram_wrapper container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <h1 class="py-2"><?php echo esc_html($program->program) ?></h1>
            <?php if(!empty($program->college)): ?>
                <h3><?php echo esc_html($program->college) ?></h3>
            <?php endif ?>
        </div>
        <div class="col-md-4 text-md-right">
            <a href="<?php global $wp; echo home_url( $wp->request ); ?>" class="btn btn-default my-3">Back to Find Your Program Search</a>
        </div>
    </div>
    <div class="card bg-default p-4 my-4">
        <?php if($program->degree): ?>
            <div class="row">
                <div class="col-md-3 text-md-right py-1">
                    <strong>Degrees or Certificates Offered:</strong>
                </div>
                <div class="col-md-9">
                    <h4><?php echo esc_html($program->degree) ?></h4>
                </div>
            </div>
        <?php endif ?>
        <?php if($program->delivery_method): ?>
            <div class="row">
                <div class="col-md-3 text-md-right py-1">
                    <strong>Delivery Method:</strong>
                </div>
                <div class="col-md-9 my-1">
                    <h4><?php echo esc_html($program->delivery_method) ?></h4>
                </div>
            </div>
        <?php endif ?>
        <?php if($program->graduate_catalog): ?>
            <div class="row">
                <div class="col-md-3 text-md-right py-1">
                    <strong>Graduate Catalog Page:</strong>
                </div>
                <div class="col-md-9 my-1">
                    <a class="btn btn-primary" href="<?php echo esc_url($program->graduate_catalog) ?>" target="_blank">Graduate Catalog Page</a>
                </div>
            </div>
        <?php endif ?>
        <?php if(!empty($program->prog_website)): ?>
            <div class="row">
                <div class="col-md-3 text-md-right">
                    <strong>Program Website:</strong>
                </div>
                <div class="col-md-9 my-1">
                    <a class="btn btn-primary" href="<?php echo esc_url(trim($program->prog_website)) ?>" target="_blank">Program Website</a>
                </div>
            </div>
        <?php endif ?>
        <?php if(!empty($program->apply_url) || !$program->require_entrance_exam): ?>
            <div class="row">
                <div class="col-md-3 text-md-right">
                </div>
                <div class="col-md-9 my-1">
                    <?php if(!empty($program->apply_url)): ?>
                    <a id="ecu-fp-apply" class="btn btn-ecu-gold" href="<?php echo esc_url(trim($program->apply_url)) ?>" target="_blank">Apply Now</a>
                    <?php endif ?>
                    <?php if(!$program->require_entrance_exam): ?>
                        <span id="ecu-fp-gre" class="btn btn-ecu-gold">GRE (or other entrance exam) Not Required!</span>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>
        <?php if(!empty($program->gainful_employment)): ?>
            <div class="row">
                <div class="col-md-3 text-md-right">
                    <strong>
                        Gainful Employment:
                        <!-- TODO integrate tooltip -->
                        <a href="" data-toggle="tooltip" id="tooltip1" data-placement="top" title="" data-original-title="The U.S. Department of Education requires colleges and universities to disclose certain information for any financial aid eligible program that, 'prepares students for gainful employment in a recognized occupation.' This information includes typical program costs; financing options; the median debt incurred by program graduates; on-time completion rates; job placement rates; and possible occupations for which program graduates are prepared."><i class="fa fa-sign"></i></a>
                    </strong>
                </div>
                <div class="col-md-9 my-1">
                    <a class="btn btn-primary" href="<?php echo esc_url($program->gainful_employment) ?>" target="_blank">Gainful Employment Information</a>
                </div>
            </div>
        <?php endif ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-primary">
                <h4>Application Deadlines</h4>
                <div class="table-responsive">
                <table class="table table-striped table-bordered bg-white">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Fall Semester</th>
                            <th>Spring Semester</th>
                            <th>1st Summer Session</th>
                            <th>2nd Summer Session</th>
                            <th>11-week Summer Session</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td class="text-left">Priority</td>
                            <td>
                                <?php echo !empty($program->fall_priority_date) ? esc_html($program->fall_priority_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->spring_priority_date) ? esc_html($program->spring_priority_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->first_sem_priority_date) ? esc_html($program->first_sem_priority_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->second_sem_priority_date) ? esc_html($program->second_sem_priority_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->eleven_week_priority_date) ? esc_html($program->eleven_week_priority_date) : '' ?>
                            </td>
                        </tr>
                        <tr>
                        <td class="text-left">Graduate School</td>
                            <td>
                                <?php echo !empty($program->fall_graduate_date) ? esc_html($program->fall_graduate_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->spring_graduate_date) ? esc_html($program->spring_graduate_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->first_sem_graduate_date) ? esc_html($program->first_sem_graduate_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->second_sem_graduate_date) ? esc_html($program->second_sem_graduate_date) : '' ?>
                            </td>
                            <td>
                                <?php echo !empty($program->eleven_week_graduate_date) ? esc_html($program->eleven_week_graduate_date) : '' ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <span class="text-white"><em>* Applications not considered for admission in this term</em></span>
            </div>
        </div>
    </div>
</div>

    <?php if($program->deadline_notes): ?>
        <div class="card bg-default p-4 my-4">
            <div class="row">
                <div class="col-md-12">
                    <h4>Application/Deadline Notes</h4>
                    <?php //echo esc_html($program->deadline_notes) ?>
                    <?php echo $program->deadline_notes ?>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12">
            <h4>Additional Information</h4>
        </div>
    </div>

    <?php if($program->addition): ?>
        <div class="row">
            <div class="col-md-3 text-md-right py-1">
                <strong>Application Materials Needed:</strong>
            </div>
            <div class="col-md-9">
                <?php //echo esc_html($program->addition) ?>
                <?php echo $program->addition ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->concentration): ?>
        <div class="row">
            <div class="col-md-3 text-md-right py-1">
                <strong>Concentrations:</strong>
            </div>
            <div class="col-md-9">
                <?php //echo esc_html($program->concentration) ?>
                <?php echo $program->concentration ?>
            </div>
        </div>
    <?php endif ?>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <h4>Graduate Program Contact</h4>
        </div>
    </div>

    <?php if($program->director): ?>
        <div class="row">
            <div class="col-md-3 text-md-right">
                <strong>Name:</strong>
            </div>
            <div class="col-md-9">
                <?php echo esc_html($program->director) ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->email): ?>
        <div class="row">
            <div class="col-md-3 text-md-right py-1">
                <strong>Email:</strong>
            </div>
            <div class="col-md-9 my-1">
                <a class="btn btn-primary" href="mailto:<?php echo esc_attr($program->email) ?>"><?php echo esc_attr($program->email) ?></a>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->dept): ?>
        <div class="row">
            <div class="col-md-3 text-md-right">
                <strong>Mailing Address:</strong>
            </div>
            <div class="col-md-9">
                <?php echo esc_html($program->dept) ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->phone): ?>
        <div class="row">
            <div class="col-md-3 text-md-right">
                <strong>Phone:</strong>
            </div>
            <div class="col-md-9">
                <?php echo esc_html($program->phone) ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->fax): ?>
        <div class="row">
            <div class="col-md-3 text-md-right">
                <strong>Fax:</strong>
            </div>
            <div class="col-md-9">
                <?php echo esc_html($program->fax) ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($program->dept_website): ?>
        <div class="row">
            <div class="col-md-3 text-md-right">
                <strong>Departmental Website:</strong>
            </div>
            <div class="col-md-9 my-1">
                <a class="btn btn-primary" href="<?php echo esc_attr($program->dept_website) ?>" target="_blank">Departmental Website</a>
            </div>
        </div>
    <?php endif ?>

</div>
