<div class="clearfix">
    <div class="table-responsive" role="tb-wrap">
        <table id="setting-api-table" class="table table-bordered table-striped dataTable responsive" data-fixed="120px">
            <thead>
            <tr>
                <th><div class="h34 align-middle">&#65279;</div></th>
                <?php
//                pr($data);die;
                foreach($office_remote_labels as $_label){  ?>
                    <th style="min-width: 150px;">
                        <?php
                        $aa = '';
                        echo $this->Form->input('OfficeRemoteLabel.' . $_label['OfficeRemoteLabel']['id'] , array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input', 'value' =>  $_label['OfficeRemoteLabel']['name'] ));

                        ?>
                    </th>
                <?php } ?>
            </tr>


            </thead>
            <tbody id="revenue-budget">
            <?php
            foreach ($data['offices'] as $_office) {
            ?>
                <tr>
                    <td>
                        <div class="h34 align-middle"><?php echo $_office['office']['Office']['name'] ?></div>
                    </td>
                    <?php  $i = 0; foreach($_office['office_remotes'] as $_office_remote){ $i++ //pr($_office_remote);die;  ?>
                    <td>
                        <?php

                        echo $this->Form->input('OfficeRemote.' . $_office['office']['Office']['id'] . '.' . $_office_remote['OfficeRemoteLabel']['id'] . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input', 'value' => !empty($_office_remote['OfficeRemote']['value']) ? $_office_remote['OfficeRemote']['value'] : '' ));


                        echo $this->Form->input('OfficeRemote.' . $_office['office']['Office']['id'] . '.' . $_office_remote['OfficeRemoteLabel']['id'] . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => !empty($_office_remote['OfficeRemote']['id']) ? $_office_remote['OfficeRemote']['id'] : ''));

                        echo $this->Form->input('OfficeRemote.' . $_office['office']['Office']['id'] . '.' . $_office_remote['OfficeRemoteLabel']['id'] . '.office_remote_label_id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => !empty($_office_remote['OfficeRemoteLabel']['id']) ? $_office_remote['OfficeRemoteLabel']['id'] : ''));



                        ?>
                    </td>
                    <?php } ?>

                </tr>
                <?php
            }
            ?>


            </tbody>
        </table>
    </div>
</div>