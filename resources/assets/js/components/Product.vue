<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="initAddProduct()" class="btn btn-primary btn-sm pull-right">
                            + Ajouter un produit
                        </button>
                        Mes produits
                    </div>

                    <div class="panel-body">

                        <table class="table table-bordered table-striped table-responsive" v-if="products.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Nom
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Prix
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            <tr v-for="(product, index) in products">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    {{ product.name }}
                                </td>
                                <td>
                                    {{ product.description }}
                                </td>
                                <td>
                                    {{ product.price }} €
                                </td>
                                <td class="action-btns">
                                    <button class="btn btn-success btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <!-- modal ajout produit -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_product_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ajouter un produit</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input type="text" name="name" id="name" placeholder="Nom du produit" class="form-control"
                                   v-model="product.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Description du produit" v-model="product.description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Prix :</label>
                            <input type="number" name="price" id="price" placeholder="Prix du produit" class="form-control"
                                   v-model="product.price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"class="btn btn-primary" @click="createProduct()">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        mounted() {
            axios.get('/products')
                .then(response => {
                    this.products = response.data.products;
                    console.log(response);
                })
        },

        data() {
            return {
                products: [],
                errors: [],
                product: {
                    name: "",
                    description: "",
                    price: ""
                }
            }
        },

        methods: {
            resetModel() {
                this.product.name = "";
                this.product.description = "";
                this.product.price = "";
            },

            initAddProduct() {
                this.errors = [];
                $("#add_product_model").modal("show");
            },

            createProduct() {
                axios.post('/products', {
                    name: this.product.name,
                    description: this.product.description,
                    price: this.product.price,
                })
                    .then(response => {
                        this.resetModel();
                        this.products.push(response.data.product);
                        $("#add_product_model").modal("hide");
                        console.log(response);
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }

                        if (error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0]);
                        }

                        if (error.response.data.errors.price) {
                            this.errors.push(error.response.data.errors.price[0]);
                        }
                    })
            }
        }
    }
</script>
<style>
    .action-btns {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .panel-heading {
        padding: 20px;
    }
</style>