"use client"

import http from "@/client/integration/http"
import { AuthResponseSchema, User } from "@/core/auth"
import React, { useMemo } from "react"
import useSWR from "swr"
import * as v from "valibot"
import { TriggerAuthContext, UserContext, UserLoadingContext } from "./context"

type AuthProviderProps = React.PropsWithChildren<{}>

export default function AuthProvider({ children }: AuthProviderProps) {
    const { data, mutate, isLoading } = useSWR("/api/auth/me", async () => {
        const response = await http.get("/api/auth/me")
        const body = await response.json()
        const validated = v.parse(AuthResponseSchema, body)
        return validated.data
    })

    const user = useMemo(
        () =>
            data ??
            ({
                _tag: "ANONYMOUS",
            } satisfies User),
        [data],
    )

    return (
        <UserLoadingContext.Provider value={isLoading}>
            <TriggerAuthContext.Provider value={mutate}>
                <UserContext.Provider value={user}>
                    {children}
                </UserContext.Provider>
            </TriggerAuthContext.Provider>
        </UserLoadingContext.Provider>
    )
}
