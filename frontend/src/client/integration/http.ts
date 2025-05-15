import { showAlert } from "@/app/_internal/utils/toast"
import ky from "ky"

const http = ky.create({
    hooks: {
        beforeError: [
            (error) => {
                showAlert({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                })
                return error
            },
        ],
    },
    timeout: 30000,
    retry: {
        methods: ["get"],
        limit: 1,
    },
})

export default http
