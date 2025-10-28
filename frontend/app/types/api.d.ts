type Filter<T extends string> = {
    [K in T]?: string;
};

type ApiResponse<T extends object> = {
    data: T;
    operations: string[];
};

type PaginatedCollection<T extends object> = {
    data: Array<T>;
    meta: {
        current_page: number;
        first_page_url: string | null;
        from: number;
        last_page: number;
        last_page_url: string | null;
        next_page_url: string | null;
        path: string;
        per_page: string;
        prev_page_url: string | null;
        to: number;
        total: number;
    };
};

// Recursively wrap all nested objects with ApiResponse
type ApiWrap<T> = T extends object
    ? T extends Array<infer U>
        ? Array<ApiWrap<U>> // Handle arrays
        : T extends Date | ((...args: unknown[]) => unknown) | RegExp
          ? T // Don't wrap special objects
          : ApiResponse<{
                [K in keyof T]: T[K] extends object
                    ? ApiWrap<T[K]> // Recursively wrap nested objects
                    : T[K];
            }>
    : T; // Primitives stay as-is

