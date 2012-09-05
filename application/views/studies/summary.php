<div class="row-fluid">

<div class="span8">
    <?php
    foreach ($programs as $program) {
        ?>
    <div class="widget-box">
        <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
            <h5>Programme d'études</h5>
        </div>
        <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th style="vertical-align: middle;">Programme</th>
                    <td><?php echo $program['name'] ; ?> (<?php echo $program['diploma'] ; ?>)</td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Cycle</th>
                    <td><?php if ($program['cycle'] == 1) {
                        echo 'Premier cycle';
                    } elseif ($program['cycle'] == 2) {
                        echo 'Deuxième cycle';
                    } elseif ($program['cycle'] == 3) {
                        echo 'Troisième cycle';
                    }?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Admission</th>
                    <td><?php echo convertSemester($program['adm_semester']); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $program['adm_type'] ; ?> </td>
                </tr>
                <?php
                if (!empty($program['faculty'])) {
                ?>
                <tr>
                    <th style="vertical-align: middle;">Faculté</th>
                    <td><?php echo $program['faculty'] ; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th style="vertical-align: middle;">Majeure</th>
                    <td><?php echo $program['major'] ; ?></td>
                </tr>
                <?php if ($program['concentrations']!=array()) { ?>
                <tr>
                    <th style="vertical-align: middle;">Concentration(s)</th>
                    <td><?php echo implode(', ', $program['concentrations']); ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</div>

<div class="span4">
    <div class="widget-box">
        <div class="widget-title">
								<span class="icon">
									<i class="icon-th"></i>
								</span>
            <h5>Statut</h5>
        </div>
        <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th style="vertical-align: middle;">Statut</th>
                    <td><?php echo $user['status'] ; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Inscrit actuellement</th>
                    <td><?php if ($user['registered']) echo 'Oui'; else echo 'Non'; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">1ère session</th>
                    <td><?php echo convertSemester($user['first_sem']); ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">Dernière session</th>
                    <td><?php echo convertSemester($user['last_sem']); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div><!-- End of row-fluid -->