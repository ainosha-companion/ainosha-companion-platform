import LoadingAnimation from "./common/loadingAnimation"

const ModalLoading = () => {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <LoadingAnimation className="w-20 h-20" />
        </div>
    )
}

export default ModalLoading
