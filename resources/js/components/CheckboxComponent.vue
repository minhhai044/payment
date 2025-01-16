<!-- <template>
    <div>
        <div v-for="checkbox in checkboxes" :key="checkbox.id">
            <input
                type="checkbox"
                :disabled="checkbox.is_disabled"
                :checked="checkbox.is_disabled"
                @change="updateCheckbox(checkbox.id)"
            />

            <label>{{ checkbox.name }}</label>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            checkboxes: [],
        };
    },
    created() {
        this.fetchCheckboxes(); // Tải danh sách checkbox

        if (window.Echo) {
            window.Echo.channel("checkbox-channel").listen(
                "CheckboxUpdated",
                (e) => {
                    const index = this.checkboxes.findIndex(
                        (cb) => cb.id === e.checkbox.id
                    );
                    if (index !== -1) {
                        this.checkboxes[index].is_disabled =
                            e.checkbox.is_disabled; // Cập nhật checkbox khi nhận sự kiện
                    }
                }
            );
        } else {
            console.log("Echo is not initialized");
        }
    },
    methods: {
        fetchCheckboxes() {
            axios
                .get("http://payment.test/api/checkboxes")
                .then((response) => {
                    console.log("Dữ liệu nhận được từ API:", response.data); // Kiểm tra dữ liệu trả về từ API
                    this.checkboxes = response.data;
                })
                .catch((error) => {
                    console.error("Lỗi khi lấy dữ liệu từ API:", error);
                });
        },
        updateCheckbox(id) {
            const checkbox = this.checkboxes.find((cb) => cb.id === id);
            if (checkbox) {
                checkbox.is_disabled = true; // Cập nhật trạng thái tại client

                // Gửi yêu cầu PUT đến server
                axios
                    .put(`http://payment.test/api/checkboxes/${id}`, {
                        is_disabled: checkbox.is_disabled,
                    })
                    .then(() => {
                        console.log("Checkbox state updated successfully");
                    })
                    .catch((error) => {
                        console.error("Error updating checkbox:", error);
                    });
            }
        },
    },
};
</script> -->
