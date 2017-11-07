<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <!--<search-bar v-on:onSearch="launchRequest()"></search-bar>-->
                <search-bar v-model="search"></search-bar>
            </div>
        </div>
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
                            <thead>
                                <tr>
                                    <th class="c-pointer" :class="{ 'is-active': filter == 'id' }" @click="filter = 'id'">
                                        No.
                                    </th>
                                    <th class="c-pointer" :class="{ 'is-active': filter == 'name' }" @click="filter = 'name'">
                                        Nom
                                    </th>
                                    <th>
                                        Description
                                    </th>
                                    <th class="c-pointer" :class="{ 'is-active': filter == 'price' }" @click="filter = 'price'">
                                        Prix
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(product, index) in productsSort">
                                <td>{{ product.id }}</td>
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
                                    <button class="btn btn-success btn-sm" @click="initUpdateProduct(index)">Editer</button>
                                    <button class="btn btn-danger btn-sm" @click="deleteProduct(index)">Supprimer</button>
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

        <!-- modal update produit -->
        <div class="modal fade" tabindex="-1" role="dialog" id="update_product_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Editer un produit</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input type="text" name="name" placeholder="Nom du produit" class="form-control"
                                   v-model="update_product.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <textarea name="description" cols="30" rows="5" class="form-control"
                                      placeholder="Description du produit" v-model="update_product.description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Prix :</label>
                            <input type="number" name="price" placeholder="Prix du produit" class="form-control"
                                   v-model="update_product.price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"class="btn btn-primary" @click="updateProduct()">Submit</button>
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
                },
                filter:"id",
                update_product: {
                    name: "",
                    description: "",
                    price: ""
                },
                search:"",
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
                this.resetModel();
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
            },

            initUpdateProduct(index) {
                this.errors = [];
              $("#update_product_model").modal("show");
              this.update_product = this.products[index];
            },

            updateProduct() {
                axios.patch('/products/' + this.update_product.id, {
                    name: this.update_product.name,
                    description: this.update_product.description,
                    price: this.update_product.price
                })
                    .then(response => {
                        $("#update_product_model").modal("hide");
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
            },

            deleteProduct(index) {
                let conf = confirm("Voulez-vous vraiment supprimer ce produit ?");
                if (conf === true) {
                    axios.delete('/products/' + this.products[index].id)
                        .then(response => {
                            this.products.splice(index, 1);
                        })
                        .catch(error => {
                            alert(error);
                    })
                }
            },

            launchRequest() {
                alert("Recherche effectué !");
            },
        },

        computed: {
            productsSort() {
                if (this.filter === "id") {
                    this.products.sort((a, b) => {
                        return a.id - b.id;
                    })
                }
                if (this.filter === 'name') {
                    this.products.sort((a, b) => {
                        let nameA = a.name.toUpperCase();
                        let nameB = b.name.toUpperCase();
                        if (nameA < nameB) {
                            return -1;
                        }
                        if (nameA > nameB) {
                            return 1;
                        }
                    });
                }
                if (this.filter === 'price') {
                    this.products.sort((a, b) => {
                        return a.price - b.price;
                    })
                }

                let products = this.products,
                    searchString = this.search;

                if (searchString.length > 0) {

                    searchString = searchString.trim().toLowerCase();

                    products = products.filter((product) => {
                        if(product.name.toLowerCase().indexOf(searchString) > -1){
                            return product;
                        }
                    });
                    return products;
                }
                return this.products;
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
    .c-pointer {
        cursor: pointer;
    }
    .is-active {
        background: black;
        color: white;
    }
    .padd-5 {
        padding: 5rem;
    }
</style>