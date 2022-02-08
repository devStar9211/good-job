<div class="clearfix">
	<div class="table-responsive" role="tb-wrap">
		<table id="tb-sales" class="table table-bordered table-striped dataTable responsive" data-fixed="120px">
			<thead>
				<th>&#65279;</th>
				<?php  if(!empty($data['offices'])): ?>
					<?php foreach($data['offices'] as $_office): ?>
						<th class="text-center">
							<?php echo $_office['Office']['name']; ?>

						</th>
					<?php endforeach ?>
				<?php else: ?>
					<th>&#65279;</th>
				<?php endif ?>
			</thead>
            <tbody id="last-highest-sales">
                <td><div class="h34 align-middle">売上</div></td>
                <?php  if(!empty($data['offices'])): ?>
                    <?php foreach($data['offices'] as $_office):
                        $id = $_office['Office']['id'];
                    ?>
                        <td>
                            <?php
                            echo $this->Form->input('PastHighestSale.'. $_office['Office']['id'] . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input price_format', 'value' => (isset($_office['PastHighestSale']['value']) ? $_office['PastHighestSale']['value'] : '')));
                            echo $this->Form->input('PastHighestSale.'. $_office['Office']['id'] .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => $_office['PastHighestSale']['id'] ));
                            ?>
                        </td>
                    <?php endforeach ?>
                <?php else: ?>
                    <td>&#65279;</td>
                <?php endif ?>
            </tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
    if (typeof format_price_all === 'function') {
        format_price_all();
    }
</script>
