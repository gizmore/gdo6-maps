<div>
<md-toolbar class="md-theme-indigo" layout-align="right">
<h1 class="md-toolbar-tools"><?= t('title_position'); ?></h1>
	<md-content layout-margin ng-controller="GDOLocationBarCtrl" class="gdo-location-bar">

		<div><?= t('position_state'); ?>: {{data.position.state.text}}</div>
		<coordinates class="gdo-position-coordinates">
			<label>Lat: </label><latitude>{{data.position.lat}}</latitude>
			<label>Lng: </label><longitude>{{data.position.lng}}</longitude>
		</coordinates>
		
		<md-input-container>
			<label>Fixture: </label>
			<md-checkbox aria-label="Toggle fix coordinates" ng-model="data.fix" ng-change="toggleFixture()"></md-checkbox>
		</md-input-container>

		<section layout="row" flex>
			<md-button ng-click="onProbe()"><?= t('btn_probe'); ?></md-button>
			<md-button ng-click="onPick()"><?= t('btn_pick'); ?></md-button>
		</section>
	</md-content>
</md-toolbar>
</div>
