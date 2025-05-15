"use client"

import { User } from "@/core/auth"
import React from "react"

const UserContext = React.createContext<User | null>(null)
function useUser() {
    const ctx = React.useContext(UserContext)
    if (ctx === null) {
        throw new Error("cannot use `useUser()` outside of `<UserContext />`")
    }
    return ctx
}
const TriggerAuthContext = React.createContext<VoidFunction | null>(null)
const useTriggerAuth = () => {
    const ctx = React.useContext(TriggerAuthContext)
    if (ctx === null) {
        throw new Error(
            "cannot use `useTriggerAuth()` outside of `<TriggerAuthContext />`",
        )
    }
    return ctx
}
const UserLoadingContext = React.createContext<boolean>(false)
const useUserLoading = () => {
    return React.useContext(UserLoadingContext)
}

export {
    TriggerAuthContext,
    UserContext,
    UserLoadingContext,
    useTriggerAuth,
    useUser,
    useUserLoading,
}
