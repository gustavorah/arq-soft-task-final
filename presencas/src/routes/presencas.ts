import { prisma } from "../prisma";
import { Elysia } from "elysia";

interface Presenca {
    ref_pessoa: number,
    ref_inscricao_evento: number
}

interface Presencas {
    ref_pessoas: number[];
    ref_inscricoes: number[];
}

export const presencasRoutes = (app: Elysia) => {
    app
        .get('/presencas', async () => {
            return await prisma.presencas.findMany();
        })
        .get('/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);

            return await prisma.presencas.findUnique({
                where: { id }
            });
        })
        .post('/presencas', async ( data ) => {
            try {
                console.log("Iniciando o processo de registro de presenças..."); // Adicionando log para depuração
                console.log(data.request);
                
                const { ref_pessoas, ref_inscricoes } = data.body as Presencas;
        
                for (let i = 0; i < ref_pessoas.length; i++) {
                    console.log(`Registrando presença para pessoa ${ref_pessoas[i]} e inscrição ${ref_inscricoes[i]}`); // Log dentro do loop
        
                    const ref_pessoa = ref_pessoas[i];
                    const ref_inscricao_evento = ref_inscricoes[i];
        
                    await prisma.presencas.create({
                        data: { ref_pessoa, ref_inscricao_evento }
                    });
                }
        
                return "Presenças registradas";
            } catch (error) {
                console.error('Erro ao salvar presença:', error); // Log de erro
                return { error: 'Erro ao salvar presença', details: error };
            }
        })
        .put('/presencas/:id', async ({ params, body }) => {
            const id = parseInt(params.id);
            const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

            const presenca = await prisma.presencas.update({
                where: { id },
                data: { ref_pessoa, ref_inscricao_evento }
            });

            return {
                ...presenca,
                id: presenca.id.toString(),
                ref_inscricao_evento: presenca.ref_inscricao_evento.toString(),
                ref_pessoa: presenca.ref_pessoa.toString()
            }
        })
        .delete('/presencas/:id', async ({ params }) => {
            const id = parseInt(params.id);
            return await prisma.presencas.delete({
                where: { id },
            })
        })
        .post('/presencas/verificar-presenca', async (data) => {
            console.log(data);
            // const { ref_pessoa, ref_inscricao_evento } = body as Presenca;

            // const presenca = await prisma.presencas.findFirstOrThrow({
            //     where: {ref_pessoa, ref_inscricao_evento}
            // });

            // return presenca ? true : false;
        })
}