export type RoleData = {
    name: string;
};
export const SessionApi = {
    store: (input: SessionCreateData) =>
        useApi<UserData>({
            url: "api/v1/session",
            method: "POST",
            data: input,
        }),
    show: () =>
        useApi<UserData>({
            url: "api/v1/session",
            method: "GET",
        }),
    destroy: () =>
        useApi<any>({
            url: "api/v1/session/logout",
            method: "POST",
        }),
};
export type SessionCreateData = {
    email: string;
    password: string;
    remember: boolean;
};
export const UserApi = {
    index: (
        query:
            | undefined
            | {
                  include?: "roles"[];
                  sorts?: ("name" | "-name")[];
                  filters?: Filter<"id" | "name" | "email">[];
                  includeOperations?: ("create" | "update" | "delete" | "*")[];
              } = undefined,
    ) =>
        useApi<any>({
            url: "api/v1/users",
            method: "GET",
            query: query,
        }),
    store: (
        input: UserData,
        query:
            | undefined
            | {
                  include?: "roles"[];
                  sorts?: ("name" | "-name")[];
                  filters?: Filter<"id" | "name" | "email">[];
                  includeOperations?: ("create" | "update" | "delete" | "*")[];
              } = undefined,
    ) =>
        useApi<UserData>({
            url: "api/v1/users",
            method: "POST",
            data: input,
            query: query,
        }),
    show: (
        parameters: { user: string },
        query:
            | undefined
            | {
                  include?: "roles"[];
                  sorts?: ("name" | "-name")[];
                  filters?: Filter<"id" | "name" | "email">[];
                  includeOperations?: ("create" | "update" | "delete" | "*")[];
              } = undefined,
    ) =>
        useApi<UserData>({
            url: "api/v1/users/{user}",
            method: "GET",
            urlParameters: parameters,
            query: query,
        }),
    update: (
        parameters: { user: string },
        input: UserData,
        query:
            | undefined
            | {
                  include?: "roles"[];
                  sorts?: ("name" | "-name")[];
                  filters?: Filter<"id" | "name" | "email">[];
                  includeOperations?: ("create" | "update" | "delete" | "*")[];
              } = undefined,
    ) =>
        useApi<UserData>({
            url: "api/v1/users/{user}",
            method: "PUT",
            data: input,
            urlParameters: parameters,
            query: query,
        }),
    destroy: (
        parameters: { user: string },
        query:
            | undefined
            | {
                  include?: "roles"[];
                  sorts?: ("name" | "-name")[];
                  filters?: Filter<"id" | "name" | "email">[];
                  includeOperations?: ("create" | "update" | "delete" | "*")[];
              } = undefined,
    ) =>
        useApi<any>({
            url: "api/v1/users/{user}",
            method: "DELETE",
            urlParameters: parameters,
            query: query,
        }),
};
export type UserData = {
    id: string;
    name: string;
    email: string;
    roles?: Array<RoleData>;
};
