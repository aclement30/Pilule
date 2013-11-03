<tr data-nrc="<?php echo $course[ 'nrc' ]; ?>" data-credits="<?php echo $course[ 'credits' ]; ?>" data-title="<?php echo $course[ 'code' ]; ?>" data-content="<?php echo $course[ 'title' ]; ?>" data-trigger="manual" data-html="true" data-placement="top">
	<td>
        <span class="code"><?php echo $course[ 'code' ]; ?></span><br />
        <span class="title"><?php echo $this->Text->truncate( $course[ 'title' ], 35 ); ?></span>
    </td>
    <td>
        <span class="nrc"><?php echo $course[ 'nrc' ]; ?></span>
        <br />
    </td>
</tr>