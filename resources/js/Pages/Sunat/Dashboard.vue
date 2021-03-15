<template>
  <app-layout>
    <h1>Dashboard Electronico</h1>
    <div>
      <div>
        <b-card-group deck>
          <b-card
            border-variant="warning"
            header="No enviado"
            header-bg-variant="warning"
            header-text-variant="white"
            align="center"
          >
            <b-card-text
              ><p>1</p><p>comprobantes</p></b-card-text
            >
          </b-card>

          <b-card
            border-variant="danger"
            header="Rechazado"
            header-bg-variant="danger"
            header-text-variant="white"
            align="center"
          >
            <b-card-text
              ><p>1</p><p>comprobantes</p></b-card-text
            >
          </b-card>

          <b-card
            border-variant="secondary"
            header="Anulado"
            header-bg-variant="secondary"
            header-text-variant="white"
            align="center"
          >
            <b-card-text
              ><p>1</p><p>comprobantes</p></b-card-text
            >
          </b-card>

          <b-card
            border-variant="success"
            header="Aceptado"
            header-bg-variant="success"
            header-text-variant="white"
            align="center"
          >
            <b-card-text
              ><p>1</p><p>comprobantes</p></b-card-text
            >
          </b-card>
        </b-card-group>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4"></h4>
            <div class="table-responsive">
              <table class="table table-centered table-nowrap mb-0">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 20px">
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck1"
                        />
                        <label class="custom-control-label" for="customCheck1"
                          >&nbsp;</label
                        >
                      </div>
                    </th>
                    <th>Order ID</th>
                    <th>Billing Name</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment Status</th>
                    <th>Payment Method</th>
                    <th>View Details</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck2"
                        />
                        <label class="custom-control-label" for="customCheck2"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2540</a
                      >
                    </td>
                    <td>Neal Matthews</td>
                    <td>07 Oct, 2019</td>
                    <td>$400</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-success font-size-12"
                        >Paid</span
                      >
                    </td>
                    <td>
                      <i class="fab fa-cc-mastercard mr-1"></i> Mastercard
                    </td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck3"
                        />
                        <label class="custom-control-label" for="customCheck3"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2541</a
                      >
                    </td>
                    <td>Jamal Burnett</td>
                    <td>07 Oct, 2019</td>
                    <td>$380</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-danger font-size-12"
                        >Chargeback</span
                      >
                    </td>
                    <td><i class="fab fa-cc-visa mr-1"></i> Visa</td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck4"
                        />
                        <label class="custom-control-label" for="customCheck4"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2542</a
                      >
                    </td>
                    <td>Juan Mitchell</td>
                    <td>06 Oct, 2019</td>
                    <td>$384</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-success font-size-12"
                        >Paid</span
                      >
                    </td>
                    <td><i class="fab fa-cc-paypal mr-1"></i> Paypal</td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck5"
                        />
                        <label class="custom-control-label" for="customCheck5"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2543</a
                      >
                    </td>
                    <td>Barry Dick</td>
                    <td>05 Oct, 2019</td>
                    <td>$412</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-success font-size-12"
                        >Paid</span
                      >
                    </td>
                    <td>
                      <i class="fab fa-cc-mastercard mr-1"></i> Mastercard
                    </td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck6"
                        />
                        <label class="custom-control-label" for="customCheck6"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2544</a
                      >
                    </td>
                    <td>Ronald Taylor</td>
                    <td>04 Oct, 2019</td>
                    <td>$404</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-warning font-size-12"
                        >Refund</span
                      >
                    </td>
                    <td><i class="fab fa-cc-visa mr-1"></i> Visa</td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck7"
                        />
                        <label class="custom-control-label" for="customCheck7"
                          >&nbsp;</label
                        >
                      </div>
                    </td>
                    <td>
                      <a
                        href="javascript: void(0);"
                        class="text-body font-weight-bold"
                        >#MB2545</a
                      >
                    </td>
                    <td>Jacob Hunter</td>
                    <td>04 Oct, 2019</td>
                    <td>$392</td>
                    <td>
                      <span
                        class="badge badge-pill badge-soft-success font-size-12"
                        >Paid</span
                      >
                    </td>
                    <td><i class="fab fa-cc-paypal mr-1"></i> Paypal</td>
                    <td>
                      <!-- Button trigger modal -->
                      <button
                        type="button"
                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                      >
                        View Details
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- end table-responsive -->
          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
};
</script>
