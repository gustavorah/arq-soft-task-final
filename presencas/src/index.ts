import { Elysia } from "elysia";
import { presencasRoutes } from "./routes/presencas";

const app = new Elysia().get("/", () => "Hello Elysia");

presencasRoutes(app);

app.listen(8005);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);
