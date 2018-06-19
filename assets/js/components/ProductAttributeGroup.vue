<template >
	<div id="product-attribute-groups" v-if='performingRequest'>
		<p>Please wait...</p>
	</div>
	<div id="product-attribute-groups" v-else>
		<div class="no-products-container" v-if='productAttributeGroups.length === 0'>
			<p>No Products Attributes Found. Please add product attributes first</p>
		</div>
		<div class="product-attributes-group-container" v-else>
			<div class="flex justify-end mb-3">
				<button class="button button-primary" @click="processData()">Save Grouping</button>
			</div>
			<div class="flex justify-between -mx-4">
				<div class="product-attribute-group__wrap px-4 w-3/5">
					<draggable class="flex -mx-4 flex-wrap"  :options='{group: "product-attribute-group-card-sortable"}' v-model="productAttributeGroups" :key="productAttributeGroups.term_id">
						<div class="col px-4 w-1/2 mb-3 " v-for="productAttributeGroup in productAttributeGroups">
							<div class="product-attribute-group__card">
								<h2 class="product-attribute-group__card-title mb-4">{{productAttributeGroup.name}}</h2>
								<draggable v-model="sampleList" class="product-attribute-group__card-body" :options='{group: "product-attribute-sortable"}'>
									<div class="py-2 border-0 border-t border-b border-solid border-grey-dark -mb-px drag" v-for="sample in sampleList" :key="sample.name">
										{{ sample.label }}
									</div>
								</draggable>
							</div>
						</div>
					</draggable>
				</div>
				<div class="product-attribute__wrap w-2/5 px-4">
					<div class="product-attribute-group__card">
						<h2 class="product-attribute-group__card-title my-4">Product Attributes</h2>
						<draggable class="product-attribute-group__card-body" :options='{group: "product-attribute-sortable"}' v-model="productAttributes">
							<div v-for="productAttribute in productAttributes" class="py-2 border-0 border-t border-b border-solid border-grey-dark bg-white -mb-px drag" :key="productAttribute.name">
								{{ productAttribute.label }}
							</div>
						</draggable>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from 'axios';
	import draggable from 'vuedraggable';

	export default {
		data (){
			async function getContent(){
				
			}
			return {
				msg: 'Hello World!',
				productAttributes: [],
				productAttributeGroups: [],
				performingRequest: true,
				sampleModel: [],
				sampleList: [
					{
						name: 100,
						label: 'Sample'
					},
					{
						name: 101,
						label: 'Sample 2'
					},

				]
			}
		},

		mounted(){
			this.getContents();
		},

		components: {
			draggable
		},

		methods: {
			async getContents() {
				try{
					let productAttributeGroupsData = await axios.get('/wp-json/wc-product-attribute-group/v1/product-attribute-group');
					this.productAttributeGroups = JSON.parse(productAttributeGroupsData.data);

					let productAttributesData = await axios.get('/wp-json/wc-product-attribute-group/v1/product-attributes');
					this.productAttributes = JSON.parse(productAttributesData.data);

					this.performingRequest = false;

				} catch( err ){
					console.log(err);
				}
			},

			processData(){
				axios.post('/wp-json/wc-product-attribute-group/v1/product-attribute-grouping',{
					productAttributeGroup: JSON.stringify(this.productAttributeGroups)
				}).then( (response) => {
					console.log(JSON.parse(response));
				});
			}
		}
	}
</script>