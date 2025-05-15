// components/ConfirmDeleteModal.tsx
"use client"

import { Dialog, DialogPanel, DialogTitle } from "@headlessui/react"
import { FiTrash2, FiX } from "react-icons/fi"

interface ConfirmDeleteModalProps {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    title?: string
    description?: string
    isLoading?: boolean
}

export default function ConfirmDeleteModal({
    isOpen,
    isLoading = false,
    onClose,
    onConfirm,
    title = "Confirm Delete",
    description = "Are you sure you want to delete this item? This action cannot be undone.",
}: ConfirmDeleteModalProps) {
    return (
        <Dialog open={isOpen} onClose={onClose} className="relative z-50">
            <div className="fixed inset-0 bg-black/50" aria-hidden="true" />
            <div className="fixed inset-0 flex items-center justify-center p-4">
                <DialogPanel className="w-full max-w-md rounded-xl bg-black p-6 shadow-2xl ring-1 ring-white/10 border border-white/10">
                    <div className="flex justify-between items-center mb-4">
                        <DialogTitle className="text-lg font-semibold text-white">
                            {title}
                        </DialogTitle>
                        <button
                            onClick={onClose}
                            className="text-gray-300 hover:text-gray-700"
                        >
                            <FiX size={20} />
                        </button>
                    </div>

                    <div className="text-sm text-gray-300 mb-6">
                        {description}
                    </div>

                    <div className="flex justify-end gap-3">
                        <button
                            onClick={onClose}
                            className="px-4 py-2 rounded-md border text-gray-800 bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button
                            onClick={() => {
                                onConfirm()
                                onClose()
                            }}
                            className="px-4 py-2 rounded-md bg-red-600 text-white flex items-center justify-center gap-2 min-w-[90px]"
                        >
                            {isLoading ? (
                                <div className="w-4 h-4 border-2 border-t-transparent border-white rounded-full animate-spin" />
                            ) : (
                                <>
                                    <FiTrash2 size={16} />
                                    Delete
                                </>
                            )}
                        </button>
                    </div>
                </DialogPanel>
            </div>
        </Dialog>
    )
}
