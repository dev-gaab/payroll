import axios from "axios";

export function historial (token, proceso) {
	axios.post('/api/historial', {
        proceso: proceso
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      .then(res => {
        console.log(res.data);
      });
}