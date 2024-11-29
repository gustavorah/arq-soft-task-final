import { prisma } from "../prisma";
import { Elysia } from "elysia";

interface Presenca {
    ref_pessoa: number,
    ref_inscricao_evento: number
}

export const presencasRoutes = (app: Elysia) => {
    app
        .get('/api/presencas', async () => {
            return await prisma.presencas.findMany();
        })
        .get('/api/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);

            return await prisma.presencas.findUnique({
                where: { id }
            });
        })
        .post('/api/presencas', async ({ body }) => {
            console.log(body);

            const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

            return await prisma.presencas.create({
                data: { ref_pessoa, ref_inscricao_evento }
            })
        })
        .put('/api/presencas/:id', async ({ params, body }) => {
            const id = parseInt(params.id);
            const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

            return await prisma.presencas.update({
                where: { id },
                data: { ref_pessoa, ref_inscricao_evento }
            })
        })
        .delete('/api/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);
            return await prisma.presencas.delete({
                where: { id },
            })
        })
}